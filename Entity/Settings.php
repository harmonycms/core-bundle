<?php

namespace Harmony\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Settings
 * @ORM\Table(name="settings", indexes={@ORM\Index(name="name_owner_id_idx", columns={"name", "owner_id"})})
 * @ORM\Entity()
 *
 * @package Harmony\Bundle\CoreBundle\Entity
 */
class Settings
{

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $value;

    /**
     * @var string
     * @ORM\Column(name="owner_id", type="string", length=255, nullable=true)
     */
    private $ownerId;

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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Settings
     */
    public function setName(string $name): Settings
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return Settings
     */
    public function setValue(string $value): Settings
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    /**
     * @param string $ownerId
     *
     * @return Settings
     */
    public function setOwnerId(string $ownerId): Settings
    {
        $this->ownerId = $ownerId;

        return $this;
    }
}