<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 13/10/15
 * Time: 09:11
 */

namespace CubicMushroom\Hexagonal\Domain\Generic;

/**
 * Interface to indicate that class is a ModelId
 *
 * @package CubicMushroom\Hexagonal
 */
interface ModelIdInterface
{
    /**
     * @return mixed
     */
    public function getValue();
}