<?php

namespace Harmony\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Helis\SettingsManagerBundle\Model\SettingModel;

/**
 * Class Settings
 * @ORM\Entity()
 * @ORM\Table(name="setting")
 *
 * @package Harmony\Bundle\CoreBundle\Entity
 */
class Settings extends SettingModel
{

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Settings
     */
    public function setId(int $id): Settings
    {
        $this->id = $id;

        return $this;
    }
}