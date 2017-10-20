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
            SymfonyBundle\FrameworkBundle\FrameworkBundle::class                => ['all' => true],
            SymfonyBundle\AsseticBundle\AsseticBundle::class                    => ['all' => true],
            SymfonyBundle\SecurityBundle\SecurityBundle::class                  => ['all' => true],
            SymfonyBundle\TwigBundle\TwigBundle::class                          => ['all' => true],
            SymfonyBundle\MonologBundle\MonologBundle::class                    => ['all' => true],
            DoctrineBundle\DoctrineBundle\DoctrineBundle::class                 => ['all' => true],
            SensioBundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],

            // Symfony CMF Standard Edition Bundles
            DoctrineBundle\PHPCRBundle\DoctrinePHPCRBundle::class               => ['all' => true],

            // Development Bundles
            SymfonyBundle\DebugBundle\DebugBundle::class             => ['dev', 'test' => true],
            SymfonyBundle\WebProfilerBundle\WebProfilerBundle::class => ['dev', 'test' => true],

            // Harmony bundles
            HarmonyCoreBundle::class                                            => ['all' => true]
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