<?php

namespace Harmony\Bundle\CoreBundle\Component\HttpKernel;

use Harmony\Sdk\Extension\AbstractExtension;
use Harmony\Sdk\Extension\ContainerExtensionInterface;
use Harmony\Sdk\Extension\ExtensionInterface;
use Harmony\Sdk\Theme\Theme;
use Harmony\Sdk\Theme\ThemeInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\MergeExtensionConfigurationPass;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

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
    const APP_VERSION = '1.0';

    /** @var string */
    protected $appName = self::APP_NAME;

    /** @var string */
    protected $appVersion = self::APP_VERSION;

    /** @var ThemeInterface[] $themes */
    protected $themes = [];

    /** @var ExtensionInterface[] $extensions */
    protected $extensions = [];

    /**
     * Boots the current kernel.
     */
    public function boot()
    {
        parent::boot();

        // init themes
        $this->initializeThemes();

        // init extensions
        $this->initializeExtensions();
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
     * Returns an array of themes to register.
     *
     * @return iterable|ThemeInterface[] An iterable of theme instances
     */
    abstract public function registerThemes(): iterable;

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
     * Get the extensions directory.
     *
     * @return string
     */
    public function getExtensionDir(): string
    {
        return $this->getProjectDir() . '/extensions';
    }

    /**
     * Returns an array of extensions to register.
     *
     * @return iterable|ExtensionInterface[] An iterable of extension instances
     */
    abstract public function registerExtensions(): iterable;

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
     * Initializes themes.
     *
     * @throws \LogicException if two themes share a common name
     */
    protected function initializeThemes(): void
    {
        // init themes
        $this->themes = [];
        /** @var Theme $theme */
        foreach ($this->registerThemes() as $theme) {
            $name = $theme->getIdentifier();
            if (isset($this->themes[$name])) {
                throw new \LogicException(sprintf('Trying to register two themes with the same name "%s"', $name));
            }
            $this->themes[$name] = $theme;
        }
    }

    /**
     * Initializes extensions.
     *
     * @throws \LogicException if two extensions share a common name
     */
    protected function initializeExtensions(): void
    {
        // init extensions
        $this->extensions = [];
        /** @var AbstractExtension $extension */
        foreach ($this->registerExtensions() as $extension) {
            $name = $extension->getIdentifier();
            if (isset($this->extensions[$name])) {
                throw new \LogicException(sprintf('Trying to register two extensions with the same name "%s"', $name));
            }
            $this->extensions[$name] = $extension;
        }
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
            $themes[$name] = \get_class($theme);
        }

        $extensions = [];
        foreach ($this->extensions as $name => $extension) {
            $extensions[$name] = \get_class($extension);
        }

        return array_merge(parent::getKernelParameters(), [
            'kernel.app_name'      => $this->appName,
            'kernel.app_version'   => $this->appVersion,
            'kernel.theme_dir'     => $this->getThemeDir(),
            'kernel.themes'        => $themes,
            'kernel.extension_dir' => $this->getExtensionDir(),
            'kernel.extensions'    => $extensions
        ]);
    }
}