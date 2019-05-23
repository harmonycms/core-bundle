<?php

namespace Harmony\Bundle\CoreBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class Config
 *
 * @package Harmony\Bundle\CoreBundle\Model
 */
class Config
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
     * @var string|null $value
     */
    protected $value;

    /**
     * @var Collection $children
     */
    protected $children;

    /**
     * @var Config $parent
     */
    protected $parent;

    /**
     * @var Extension $extension
     */
    protected $extension;

    /**
     * Config constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
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
     * @return Config
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
     * @return Config
     */
    public function setName(?string $name): Config
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get Value
     *
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * Set Value
     *
     * @param string|null $value
     *
     * @return Config
     */
    public function setValue(?string $value): Config
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get Children
     *
     * @return Collection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * Set Children
     *
     * @param Collection $children
     *
     * @return Config
     */
    public function setChildren(Collection $children): Config
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Add children
     *
     * @param Config $children
     *
     * @return Config
     */
    public function addChildren(Config $children): Config
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param Config $children
     *
     * @return Config
     */
    public function removeChildren(Config $children): Config
    {
        $this->children->removeElement($children);

        return $this;
    }

    /**
     * Get Parent
     *
     * @return Config
     */
    public function getParent(): Config
    {
        return $this->parent;
    }

    /**
     * Set Parent
     *
     * @param Config $parent
     *
     * @return Config
     */
    public function setParent(Config $parent): Config
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get Extension
     *
     * @return Extension
     */
    public function getExtension(): Extension
    {
        return $this->extension;
    }

    /**
     * Set Extension
     *
     * @param Extension $extension
     *
     * @return Config
     */
    public function setExtension(Extension $extension): Config
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * @return array|int|string|null
     */
    public function getConfigTree()
    {
        if (count($this->children) > 0) {
            $configArray = [];
            foreach ($this->children as $child) {
                $configArray[$child->getName()] = $child->getConfigTree();
            }

            return $configArray;
        }

        if (is_numeric($this->value)) {
            $this->value = intval($this->value);
        }

        return $this->value;
    }
}