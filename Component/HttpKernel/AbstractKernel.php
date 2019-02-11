<?php

namespace Harmony\Bundle\CoreBundle\Component\HttpKernel;

use Harmony\Sdk\Theme\Theme;
use Harmony\Sdk\Theme\ThemeInterface;
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

    /**
     * Boots the current kernel.
     */
    public function boot()
    {
        parent::boot();

        // init themes
        $this->initializeThemes();
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

        return array_merge(parent::getKernelParameters(), [
            'kernel.app_name'    => $this->appName,
            'kernel.app_version' => $this->appVersion,
            'kernel.theme_dir'   => $this->getThemeDir(),
            'kernel.themes'      => $themes
        ]);
    }
}