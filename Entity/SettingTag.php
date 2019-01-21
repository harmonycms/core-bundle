<?php

namespace Harmony\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Helis\SettingsManagerBundle\Model\TagModel;

/**
 * Class SettingTag
 * @ORM\Entity()
 * @ORM\Table(name="setting_tag")
 *
 * @package Harmony\Bundle\CoreBundle\Entity
 */
class SettingTag extends TagModel
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
}