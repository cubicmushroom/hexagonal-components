<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 01/10/15
 * Time: 16:34
 */

namespace CubicMushroom\Hexagonal\Domain\Generic;

/**
 * Interface ModelInterface
 *
 * @package CubicMushroom\Hexagonal
 */
interface ModelInterface
{
    /**
     * @return ModelIdInterface
     */
    public function id();


    /**
     * @param ModelIdInterface $id
     *
     * @return $this
     */
    public function assignId(ModelIdInterface $id);
}