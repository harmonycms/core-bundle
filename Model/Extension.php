<?php

namespace Harmony\Bundle\CoreBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class Extension
 *
 * @package Harmony\Bundle\CoreBundle\Model
 */
class Extension implements ExtensionInterface
{

    /**
     * @var int|string|null $id
     */
    protected $id;

    /**
     * @var string|null $name
     */
    protected $name;

    /**
     * @var Collection $configs
     */
    protected $configs;

    /**
     * Extension constructor.
     */
    public function __construct()
    {
        $this->configs = new ArrayCollection();
    }

    /**
     * Get Id
     *
     * @return int|string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Id
     *
     * @param int|string|null $id
     *
     * @return Extension
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get Name
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set Name
     *
     * @param string|null $name
     *
     * @return Extension
     */
    public function setName(?string $name): Extension
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Add configs
     *
     * @param Config $config
     *
     * @return Extension
     */
    public function addConfig(Config $config): Extension
    {
        $config->setExtension($this);

        $this->configs[] = $config;

        return $this;
    }

    /**
     * Remove configs
     *
     * @param Config $configs
     *
     * @return Extension
     */
    public function removeConfig(Config $configs): Extension
    {
        $this->configs->removeElement($configs);

        return $this;
    }

    /**
     * Get Configs
     *
     * @return Collection
     */
    public function getConfigs(): Collection
    {
        return $this->configs;
    }

    /**
     * Set Configs
     *
     * @param Collection $configs
     *
     * @return Extension
     */
    public function setConfigs(Collection $configs): Extension
    {
        $this->configs = $configs;

        return $this;
    }

    /**
     * Get root configs
     *
     * @return ArrayCollection
     */
    public function getRootConfigs(): ArrayCollection
    {
        $configs = new ArrayCollection();

        foreach ($this->configs as $config) {
            if (false == $config->getParent()) {
                $configs[] = $config;
            }
        }

        return $configs;
    }
}