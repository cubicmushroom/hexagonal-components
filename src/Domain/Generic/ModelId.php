<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 01/10/15
 * Time: 16:27
 */

namespace CubicMushroom\Hexagonal\Domain\Generic;

/**
 * Class ModelId
 *
 * @package CubicMushroom\Hexagonal
 *
 * @see \spec\CubicMushroom\Hexagonal\Domain\Generic\ModelIdTestSpec
 */
abstract class ModelId implements ModelIdInterface
{
    // -----------------------------------------------------------------------------------------------------------------
    // Properties
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * @var mixed
     */
    protected $value;


    // -----------------------------------------------------------------------------------------------------------------
    // Constructor
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }


    // -----------------------------------------------------------------------------------------------------------------
    // Comparison methods
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * @return string
     */
    protected function getModelClass()
    {
        $idClass = get_class($this);

        return preg_replace('/Id$/', '', $idClass);
    }


    /**
     * Compares this product id with the provided $id
     *
     * $id can be either an integer, ProductId or Product
     *
     * @param mixed|ModelIdInterface|ModelInterface $id
     *
     * @return bool
     */
    public function equals($id)
    {
        if ($id instanceof ModelIdInterface) {

            return ($id->getValue() === $this->value);
        } elseif (is_a($id, $this->getModelClass(), true)) {

            $model = $id;

            if (!$model instanceof ModelInterface) {
                throw new \RuntimeException(sprintf('Model class %s must implement ModelInterface', get_class($model)));
            }

            return ($this->equals($model->id()->getValue()));
        }

        return ($this->getValue() === (string)$id);
    }


    // -----------------------------------------------------------------------------------------------------------------
    // Getters
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }


    /**
     * @return string
     */
    function __toString()
    {
        return (string) $this->getValue();
    }


}