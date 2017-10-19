<?php

namespace Harmony\Bundle\CoreBundle\Composer;

use Composer\Script\Event;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

/**
 * Class AbstractScriptHandler
 *
 * @package Harmony\Bundle\CoreBundle\Composer
 */
abstract class AbstractScriptHandler
{

    /**
     * Composer variables are declared static so that an event could update
     * a composer.json and set new options, making them immediately available
     * to forthcoming listeners.
     */
    protected static $options
        = [
            'symfony-app-dir'        => 'app',
            'symfony-web-dir'        => 'web',
            'symfony-assets-install' => 'hard',
            'symfony-cache-warmup'   => false
        ];

    /**
     * Returns a relative path to the directory that contains the `console` command.
     *
     * @param Event  $event      The command event
     * @param string $actionName The name of the action
     *
     * @return string|null The path to the console directory, null if not found
     */
    protected static function getConsoleDir(Event $event, $actionName)
    {
        $options = static::getOptions($event);
        if (static::useNewDirectoryStructure($options)) {
            if (!static::hasDirectory($event, 'symfony-bin-dir', $options['symfony-bin-dir'], $actionName)) {
                return null;
            }

            return $options['symfony-bin-dir'];
        }
        if (!static::hasDirectory($event, 'symfony-app-dir', $options['symfony-app-dir'], 'execute command')) {
            return null;
        }

        return $options['symfony-app-dir'];
    }

    /**
     * @param Event $event
     *
     * @return array
     */
    protected static function getOptions(Event $event): array
    {
        $options                           = array_merge(static::$options,
            $event->getComposer()->getPackage()->getExtra());
        $options['symfony-assets-install'] = getenv('SYMFONY_ASSETS_INSTALL') ?: $options['symfony-assets-install'];
        $options['symfony-cache-warmup']   = getenv('SYMFONY_CACHE_WARMUP') ?: $options['symfony-cache-warmup'];
        $options['process-timeout']        = $event->getComposer()->getConfig()->get('process-timeout');
        $options['vendor-dir']             = $event->getComposer()->getConfig()->get('vendor-dir');

        return $options;
    }

    /**
     * @param Event $event
     * @param       $consoleDir
     * @param       $cmd
     * @param int   $timeout
     */
    protected static function executeCommand(Event $event, $consoleDir, $cmd, $timeout = 300)
    {
        $php     = escapeshellarg(static::getPhp(false));
        $phpArgs = implode(' ', array_map('escapeshellarg', static::getPhpArguments()));
        $console = escapeshellarg($consoleDir . '/console');
        if ($event->getIO()->isDecorated()) {
            $console .= ' --ansi';
        }
        $process = new Process($php . ($phpArgs ? ' ' . $phpArgs : '') . ' ' . $console . ' ' . $cmd, null, null, null,
            $timeout);
        $process->run(function ($type, $buffer) use ($event) {
            $event->getIO()->write($buffer, false);
        });
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(sprintf("An error occurred when executing the \"%s\" command:\n\n%s\n\n%s",
                escapeshellarg($cmd), self::removeDecoration($process->getOutput()),
                self::removeDecoration($process->getErrorOutput())));
        }
    }

    /**
     * @param bool $includeArgs
     *
     * @return false|string
     */
    protected static function getPhp($includeArgs = true)
    {
        $phpFinder = new PhpExecutableFinder();
        if (!$phpPath = $phpFinder->find($includeArgs)) {
            throw new \RuntimeException('The php executable could not be found, add it to your PATH environment variable and try again');
        }

        return $phpPath;
    }

    /**
     * @return array
     */
    protected static function getPhpArguments(): array
    {
        $ini       = null;
        $arguments = [];
        $phpFinder = new PhpExecutableFinder();
        if (method_exists($phpFinder, 'findArguments')) {
            $arguments = $phpFinder->findArguments();
        }
        if ($env = getenv('COMPOSER_ORIGINAL_INIS')) {
            $paths = explode(PATH_SEPARATOR, $env);
            $ini   = array_shift($paths);
        } else {
            $ini = php_ini_loaded_file();
        }
        if ($ini) {
            $arguments[] = '--php-ini=' . $ini;
        }

        return $arguments;
    }

    /**
     * @param Event $event
     * @param       $configName
     * @param       $path
     * @param       $actionName
     *
     * @return bool
     */
    protected static function hasDirectory(Event $event, $configName, $path, $actionName): bool
    {
        if (!is_dir($path)) {
            $event->getIO()->write(sprintf('The %s (%s) specified in composer.json was not found in %s, can not %s.',
                $configName, $path, getcwd(), $actionName));

            return false;
        }

        return true;
    }

    /**
     * Returns true if the new directory structure is used.
     *
     * @param array $options Composer options
     *
     * @return bool
     */
    protected static function useNewDirectoryStructure(array $options): bool
    {
        return isset($options['symfony-var-dir']) && is_dir($options['symfony-var-dir']);
    }

    /**
     * Returns true if the application bespoke autoloader is used.
     *
     * @param array $options Composer options
     *
     * @return bool
     */
    protected static function useSymfonyAutoloader(array $options): bool
    {
        return isset($options['symfony-app-dir']) && is_file($options['symfony-app-dir'] . '/autoload.php');
    }

    /**
     * @param $string
     *
     * @return mixed
     */
    private static function removeDecoration($string)
    {
        return preg_replace("/\033\[[^m]*m/", '', $string);
    }
}