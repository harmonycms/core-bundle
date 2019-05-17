<?php

namespace Harmony\Bundle\CoreBundle\Component\HttpKernel;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Doctrine\Bundle\MongoDBBundle\DependencyInjection\Compiler\DoctrineMongoDBMappingsPass;
use Harmony\Bundle\CoreBundle\HarmonyCoreBundle;
use Harmony\Bundle\CoreBundle\Provider\ContainerBuilderOdm;
use Harmony\Bundle\CoreBundle\Provider\ContainerBuilderOrm;
use Harmony\Sdk\Extension\AbstractExtension;
use Harmony\Sdk\Extension\BootableInterface;
use Harmony\Sdk\Extension\BuildableInterface;
use Harmony\Sdk\Extension\Component;
use Harmony\Sdk\Extension\ContainerExtensionInterface;
use Harmony\Sdk\Extension\ExtensionInterface;
use Harmony\Sdk\Theme\Theme;
use Harmony\Sdk\Theme\ThemeInterface;
use InvalidArgumentException;
use LogicException;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\TaggedContainerInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\DependencyInjection\MergeExtensionConfigurationPass;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use function array_merge;
use function class_exists;
use function count;
use function explode;
use function file_exists;
use function get_class;
use function microtime;
use function putenv;
use function sprintf;
use function strpos;
use function substr;

/**
 * Class AbstractKernel
 *
 * @package Harmony\Bundle\CoreBundle\Component\HttpKernel
 */
abstract class AbstractKernel extends BaseKernel
{

    /** Constants */
    const KERNEL_NAME = 'harmony';
    const APP_NAME    = 'Harmony';

    /** @var string */
    protected $appName = self::APP_NAME;

    /** @var string */
    protected $appVersion = HarmonyCoreBundle::VERSION;

    /** @var ThemeInterface[] $themes */
    protected $themes = [];

    /** @var ExtensionInterface[] $extensions */
    protected $extensions = [];

    /** @var ContainerInterface $container */
    protected $container;

    /** @var int $requestStackSize */
    private $requestStackSize = 0;

    /** @var bool $resetServices */
    private $resetServices = false;

    /**
     * AbstractKernel constructor.
     *
     * @param string $environment
     * @param bool   $debug
     */
    public function __construct(string $environment, bool $debug)
    {
        parent::__construct($environment, $debug);
    }

    /**
     * Returns an array of themes to register.
     *
     * @return iterable|ThemeInterface[] An iterable of theme instances
     */
    abstract public function registerThemes(): iterable;

    /**
     * Returns an array of extensions to register.
     *
     * @return iterable|ExtensionInterface[] An iterable of extension instances
     */
    abstract public function registerExtensions(): iterable;

    /**
     * Boots the current kernel.
     */
    public function boot()
    {
        if (true === $this->booted) {
            if (!$this->requestStackSize && $this->resetServices) {
                if ($this->container->has('services_resetter')) {
                    $this->container->get('services_resetter')->reset();
                }
                $this->resetServices = false;
                if ($this->debug) {
                    $this->startTime = microtime(true);
                }
            }

            return;
        }
        if ($this->debug) {
            $this->startTime = microtime(true);
        }
        if ($this->debug && !isset($_ENV['SHELL_VERBOSITY']) && !isset($_SERVER['SHELL_VERBOSITY'])) {
            putenv('SHELL_VERBOSITY=3');
            $_ENV['SHELL_VERBOSITY']    = 3;
            $_SERVER['SHELL_VERBOSITY'] = 3;
        }

        // init bundles
        $this->initializeBundles();

        // init themes
        $this->initializeThemes();

        // init extensions
        $this->initializeExtensions();

        // init container
        $this->initializeContainer();

        // Boot bundles
        foreach ($this->getBundles() as $bundle) {
            $bundle->setContainer($this->container);
            $bundle->boot();
        }

        // Boot extensions
        foreach ($this->getExtensions() as $extension) {
            if ($extension instanceof BootableInterface) {
                $extension->setContainer($this->container);
                $extension->boot();
            }
        }

        $this->booted = true;
    }

    /**
     * Gets the application name of the kernel.
     *
     * @return string The kernel name
     */
    public function getAppName(): string
    {
        return $this->appName;
    }

    /**
     * Gets the application version of the Application.
     *
     * @return string The version number
     */
    public function getAppVersion(): string
    {
        return $this->appVersion;
    }

    /**
     * Get the themes directory.
     *
     * @return string
     */
    public function getThemeDir(): string
    {
        return $this->getProjectDir() . '/themes';
    }

    /**
     * Gets the registered theme instances.
     *
     * @return ThemeInterface[] An array of registered theme instances
     */
    public function getThemes(): array
    {
        return $this->themes;
    }

    /**
     * Returns a theme.
     *
     * @param string $name
     *
     * @return ThemeInterface|null
     */
    public function getTheme(string $name): ?ThemeInterface
    {
        return $this->themes[$name] ?? null;
    }

    /**
     * Get the extensions directory.
     *
     * @return string
     */
    public function getExtensionDir(): string
    {
        return $this->getProjectDir() . '/extensions';
    }

    /**
     * Gets the registered extension instances.
     *
     * @return ExtensionInterface[] An array of registered extension instances
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * Returns an extension.
     *
     * @param string $name
     *
     * @return ExtensionInterface|null
     */
    public function getExtension(string $name): ?ExtensionInterface
    {
        return $this->extensions[$name] ?? null;
    }

    /**
     * Returns the file path for a given bundle resource.
     * A Resource can be a file or a directory.
     * The resource name must follow the following pattern:
     *     "@BundleName/path/to/a/file.something"
     * where BundleName is the name of the bundle
     * and the remaining part is the relative path in the bundle.
     * If $dir is passed, and the first segment of the path is "Resources",
     * this method will look for a file named:
     *     $dir/<BundleName>/path/without/Resources
     * before looking in the bundle resource folder.
     *
     * @param string $name  A resource name to locate
     * @param string $dir   A directory where to look for the resource first
     * @param bool   $first Whether to return the first path or paths for all matching bundles
     *
     * @return string|array The absolute path of the resource or an array if $first is false
     * @throws InvalidArgumentException if the file cannot be found or the name is not valid
     * @throws RuntimeException         if the name contains invalid/unsafe characters
     */
    public function locateResource($name, $dir = null, $first = true)
    {
        if ('@' !== $name[0]) {
            throw new InvalidArgumentException(sprintf('A resource name must start with @ ("%s" given).', $name));
        }

        if (false !== strpos($name, '..')) {
            throw new RuntimeException(sprintf('File name "%s" contains invalid characters (..).', $name));
        }

        $bundleName = substr($name, 1);
        $path       = '';
        if (false !== strpos($bundleName, '/')) {
            list($bundleName, $path) = explode('/', $bundleName, 2);
        }

        $isResource   = 0 === strpos($path, 'Resources') && null !== $dir;
        $overridePath = substr($path, 9);
        // Override: try to load `bundle` from extension first
        if (null === $bundle = $this->getExtension($bundleName)) {
            $bundle = $this->getBundle($bundleName);
        }
        $files = [];

        if ($isResource && file_exists($file = $dir . '/' . $bundle->getName() . $overridePath)) {
            $files[] = $file;
        }

        if (file_exists($file = $bundle->getPath() . '/' . $path)) {
            if ($first && !$isResource) {
                return $file;
            }
            $files[] = $file;
        }

        if (count($files) > 0) {
            return $first && $isResource ? $files[0] : $files;
        }

        throw new InvalidArgumentException(sprintf('Unable to find file "%s".', $name));
    }

    /**
     * Initializes themes.
     *
     * @throws LogicException if two themes share a common name
     */
    protected function initializeThemes(): void
    {
        // init themes
        $this->themes = [];
        /** @var Theme $theme */
        foreach ($this->registerThemes() as $theme) {
            $name = $theme->getIdentifier();
            if (isset($this->themes[$name])) {
                throw new LogicException(sprintf('Trying to register two themes with the same name "%s"', $name));
            }
            $this->themes[$name] = $theme;
        }
    }

    /**
     * Initializes extensions.
     *
     * @throws LogicException if two extensions share a common name
     */
    protected function initializeExtensions(): void
    {
        // init extensions
        $this->extensions = [];
        /** @var AbstractExtension $extension */
        foreach ($this->registerExtensions() as $extension) {
            $name = $extension->getIdentifier();
            if (isset($this->extensions[$name])) {
                throw new LogicException(sprintf('Trying to register two extensions with the same name "%s"', $name));
            }

            $this->extensions[$name] = $extension;
        }
    }

    /**
     * Gets a new ContainerBuilder instance used to build the service container.
     *
     * @return TaggedContainerInterface
     */
    protected function getContainerBuilder(): TaggedContainerInterface
    {
        $bundles = $this->getKernelParameters()['kernel.bundles'];
        if (class_exists(DoctrineOrmMappingsPass::class) && isset($bundles['DoctrineBundle'])) {
            $container = new ContainerBuilderOrm();
        } elseif (class_exists(DoctrineOrmMappingsPass::class) && isset($bundles['DoctrineBundle'])) {
            $container = new ContainerBuilderOdm();
        }

        return parent::getContainerBuilder();
    }

    /**
     * Prepares the ContainerBuilder before it is compiled.
     *
     * @param ContainerBuilder $container
     */
    protected function prepareContainer(ContainerBuilder $container)
    {
        foreach ($this->bundles as $bundle) {
            if ($containerExtension = $bundle->getContainerExtension()) {
                $container->registerExtension($containerExtension);
            }

            if ($this->debug) {
                $container->addObjectResource($bundle);
            }
        }

        foreach (array_merge($this->bundles, $this->extensions) as $item) {
            if ($item instanceof BuildableInterface || $item instanceof BundleInterface) {
                $item->build($container);
            }
        }

        $this->build($container);

        $extensions = [];
        foreach ($container->getExtensions() as $containerExtension) {
            $extensions[] = $containerExtension->getAlias();
        }

        // ensure these extensions are implicitly loaded
        $container->getCompilerPassConfig()->setMergePass(new MergeExtensionConfigurationPass($extensions));
    }

    /**
     * The extension point similar to the Bundle::build() method.
     * Use this method to register compiler passes and manipulate the container during the building process.
     *
     * @param ContainerBuilder $container
     */
    protected function build(ContainerBuilder $container)
    {
        parent::build($container);

        $containerExtensions = [];
        foreach ($this->getExtensions() as $extension) {
            if ($extension instanceof ContainerExtensionInterface && $containerExtension
                    = $extension->getContainerExtension()) {
                $container->registerExtension($containerExtension);

                // Debug
                if ($this->debug) {
                    $container->addObjectResource($extension);
                }

                $containerExtensions[] = $containerExtension->getAlias();
            }
        }

        // ensure these extensions are implicitly loaded
        $container->getCompilerPassConfig()->setMergePass(new MergeExtensionConfigurationPass($containerExtensions));
    }

    /**
     * Returns the kernel parameters.
     *
     * @return array An array of kernel parameters
     */
    protected function getKernelParameters(): array
    {
        $themes = [];
        foreach ($this->themes as $name => $theme) {
            $themes[$name] = get_class($theme);
        }

        $extensions         = [];
        $extensionsMetadata = [];
        foreach ($this->extensions as $name => $extension) {
            $extensions[$name]         = get_class($extension);
            $extensionsMetadata[$name] = ['path' => $extension->getPath(), 'namespace' => $extension->getIdentifier()];
            /** @var Component $extension */
            if (AbstractExtension::COMPONENT === $extension->getExtensionType()) {
                $extensionsMetadata[$name]['type'] = $extension->getType();
            }
        }

        return array_merge(parent::getKernelParameters(), [
            'kernel.app_name'            => $this->appName,
            'kernel.app_version'         => $this->appVersion,
            'kernel.theme_dir'           => $this->getThemeDir(),
            'kernel.themes'              => $themes,
            'kernel.extension_dir'       => $this->getExtensionDir(),
            'kernel.extensions'          => $extensions,
            'kernel.extensions_metadata' => $extensionsMetadata
        ]);
    }
}