<?php

namespace Harmony\Bundle\CoreBundle\Model;

/**
 * Class Parameter
 *
 * @package Harmony\Bundle\CoreBundle\Model
 */
abstract class Parameter implements ParameterInterface
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
     * @return Parameter
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
     * @return Parameter
     */
    public function setName(?string $name): Parameter
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
     * @return Parameter
     */
    public function setValue(?string $value): Parameter
    {
        $this->value = $value;

        return $this;
    }
}