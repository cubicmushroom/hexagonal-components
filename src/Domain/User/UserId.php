<?php

namespace CubicMushroom\Hexagonal\Domain\User;

use CubicMushroom\Hexagonal\Domain\Generic\ModelId;

/**
 * UserId Value Object
 *
 * @package CubicMushroom\Hexagonal
 */
class UserId extends ModelId
{
    // -----------------------------------------------------------------------------------------------------------------
    // Getters and Setters
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->getValue();
    }
}
