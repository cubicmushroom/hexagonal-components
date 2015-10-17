<?php

namespace CubicMushroom\Hexagonal\Domain\User;

/**
 * UserId Value Object
 *
 * @package CubicMushroom\Hexagonal
 */
class UserId
{
    // -----------------------------------------------------------------------------------------------------------------
    // Properties
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * @var mixed
     */
    protected $id;


    // -----------------------------------------------------------------------------------------------------------------
    // Constructor
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * UserId constructor.
     *
     * @param $id
     *
     * @throws \InvalidArgumentException if $id is empty
     */
    public function __construct($id)
    {
        if (empty($id)) {
            throw new \InvalidArgumentException('"$id" is empty');
        }

        $this->id = $id;
    }


    // -----------------------------------------------------------------------------------------------------------------
    // Getters and Setters
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
