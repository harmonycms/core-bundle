<?php

namespace Harmony\Bundle\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Helis\SettingsManagerBundle\Model\SettingModel;

/**
 * Class Settings
 * @ORM\Entity()
 * @ORM\Table(name="setting")
 *
 * @package Harmony\Bundle\CoreBundle\Entity
 */
class Setting extends SettingModel
{

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection|SettingTag[]
     * @ORM\ManyToMany(targetEntity="Harmony\Bundle\CoreBundle\Entity\SettingTag", cascade={"persist"}, fetch="EAGER")
     */
    protected $tags;

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
     * @return Setting
     */
    public function setId(int $id): Setting
    {
        $this->id = $id;

        return $this;
    }
}