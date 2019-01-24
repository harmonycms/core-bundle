<?php

namespace Harmony\Bundle\CoreBundle\Manager;

use Helis\SettingsManagerBundle\Model\SettingModel;
use Helis\SettingsManagerBundle\Settings\SettingsManager as BaseManager;

/**
 * Class SettingsManager
 *
 * @package Harmony\Bundle\CoreBundle
 */
class SettingsManager extends BaseManager
{

    /**
     * Get a single setting from a domain (optional).
     *
     * @param string $name
     * @param string $domain
     *
     * @return SettingModel|mixed
     */
    public function getSetting(string $name, string $domain = 'default')
    {
        // Only 1 value by domain can exists
        $settings = $this->getSettingsByName([$domain], [$name]);

        // Get the first and unique value of array
        return array_shift($settings);
    }
}