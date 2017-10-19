<?php

namespace Harmony\Bundle\CoreBundle\Component\HttpKernel;

use Doctrine\Bundle as DoctrineBundle;
use Harmony\Bundle\CoreBundle\HarmonyCoreBundle;
use Sensio\Bundle as SensioBundle;
use Symfony\Bundle as SymfonyBundle;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
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
    const APP_VERSION = '0.1';

    /** @var string */
    protected $appName = self::APP_NAME;

    /** @var string */
    protected $appVersion = self::APP_VERSION;

    /**
     * Returns an array of bundles to register.
     *
     * @return BundleInterface[] An array of bundle instances.
     */
    public function registerBundles(): array
    {
        return [
            // Symfony Standard Edition Bundles
            new SymfonyBundle\FrameworkBundle\FrameworkBundle(),
            new SymfonyBundle\AsseticBundle\AsseticBundle(),
            new SymfonyBundle\SecurityBundle\SecurityBundle(),
            new SymfonyBundle\TwigBundle\TwigBundle(),
            new SymfonyBundle\MonologBundle\MonologBundle(),
            new DoctrineBundle\DoctrineBundle\DoctrineBundle(),
            new SensioBundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            // Harmony bundles
            new HarmonyCoreBundle()
        ];
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
     * Gets the name of the kernel.
     *
     * @return string The kernel name
     */
    public function getName(): string
    {
        return $this->name = self::KERNEL_NAME;
    }

    /**
     * Returns an array of dev bundles to register.
     *
     * @return array
     */
    protected function registerDevBundles(): array
    {
        return [
            new SymfonyBundle\DebugBundle\DebugBundle(),
            new SymfonyBundle\WebProfilerBundle\WebProfilerBundle()
        ];
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
            'kernel.app_version' => $this->appVersion
        ]);
    }
}