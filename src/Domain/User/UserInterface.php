<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 06/10/15
 * Time: 11:56
 */

namespace CubicMushroom\Hexagonal\Domain\User;

/**
 * Interface for platform's user object
 *
 * @package CubicMushroom\Hexagonal
 */
interface UserInterface
{
    /**
     * @return mixed
     */
    public function getId();
}