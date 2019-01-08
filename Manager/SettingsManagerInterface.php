<?php

namespace Harmony\Bundle\CoreBundle\Manager;

use FOS\UserBundle\Model\UserInterface;

/**
 * Interface SettingsManagerInterface
 *
 * @package Harmony\Bundle\AdminBundle\Manager
 */
interface SettingsManagerInterface
{

    const SCOPE_ALL    = 'all';
    const SCOPE_GLOBAL = 'global';
    const SCOPE_USER   = 'user';

    /**
     * Returns setting value by its name.
     *
     * @param string             $name
     * @param UserInterface|null $user
     * @param mixed|null         $default value to return if the setting is not set
     *
     * @return mixed
     */
    public function get($name, UserInterface $user = null, $default = null);

    /**
     * Returns all settings as associative name-value array.
     *
     * @param UserInterface|null $user
     *
     * @return array
     */
    public function all(UserInterface $user = null);

    /**
     * Sets setting value by its name.
     *
     * @param string             $name
     * @param mixed              $value
     * @param UserInterface|null $user
     *
     * @return SettingsManagerInterface
     */
    public function set($name, $value, UserInterface $user = null);

    /**
     * Sets settings' values from associative name-value array.
     *
     * @param array              $settings
     * @param UserInterface|null $user
     *
     * @return SettingsManagerInterface
     */
    public function setMany(array $settings, UserInterface $user = null);

    /**
     * Clears setting value.
     *
     * @param string             $name
     * @param UserInterface|null $user
     *
     * @return SettingsManagerInterface
     */
    public function clear($name, UserInterface $user = null);
}