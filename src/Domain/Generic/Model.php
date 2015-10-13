<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 13/10/15
 * Time: 09:26
 */

namespace CubicMushroom\Hexagonal\Domain\Generic;

use CubicMushroom\Hexagonal\Exception\Domain\Generic\IdAlreadyAssignedException;

/**
 * Class Model
 *
 * @package CubicMushroom\Hexagonal
 *
 * @see \spec\CubicMushroom\Hexagonal\Domain\Generic\ModelTestSpec
 */
abstract class Model implements ModelInterface
{
    /**
     * @var ModelId
     */
    protected $id;

    /**
     * @return ModelIdInterface
     */
    public function id()
    {
        return $this->id;
    }


    /**
     * @param ModelIdInterface $id
     *
     * @return $this
     *
     * @throws IdAlreadyAssignedException if attempting to re-assign $id
     */
    public function assignId(ModelIdInterface $id)
    {
        if (isset($this->id)) {
            throw new IdAlreadyAssignedException("\$id of value {$this->id->getValue()} already assigned");
        }

        $this->id = $id;

        return $this;
    }

}