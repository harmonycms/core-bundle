<?php

namespace Harmony\Bundle\CoreBundle\Composer;

use Composer\Script\Event;
use Incenteev\ParameterHandler\ScriptHandler as ParameterHandlerScriptHandler;
use Sensio\Bundle\DistributionBundle\Composer\ScriptHandler as DistributionBundleScriptHandler;

/**
 * Class ScriptHandler
 *
 * @package Harmony\Bundle\CoreBundle\Composer
 */
class ScriptHandler extends AbstractScriptHandler
{

    /**
     * Occurs after the install command has been executed with a lock file present.
     * Occurs before the update command is executed, or before the install command is executed without a lock file
     * present.
     *
     * @param Event $event
     */
    public static function handleCommandScripts(Event $event)
    {
        ParameterHandlerScriptHandler::buildParameters($event);
        DistributionBundleScriptHandler::buildBootstrap($event);
        DistributionBundleScriptHandler::clearCache($event);
        DistributionBundleScriptHandler::installRequirementsFile($event);
        DistributionBundleScriptHandler::prepareDeploymentTarget($event);
    }
}