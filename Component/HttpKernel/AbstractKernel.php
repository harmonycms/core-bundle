<?php

namespace Harmony\Bundle\CoreBundle\Component\HttpKernel;

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
     * Returns the kernel parameters.
     *
     * @return array An array of kernel parameters
     */
    protected function getKernelParameters(): array
    {
        return array_merge(parent::getKernelParameters(), [
            'kernel.app_name'    => $this->appName,
            'kernel.app_version' => $this->appVersion,
            'kernel.theme_dir'   => $this->getThemeDir()
        ]);
    }
}