<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 13/10/15
 * Time: 09:33
 */

namespace CubicMushroom\Hexagonal\Exception\Domain\Generic;

/**
 * Exception thrown when attempting to re-assign a model's id
 *
 * @package CubicMushroom\Hexagonal
 */
class IdAlreadyAssignedException extends AbstractGenericException
{
}