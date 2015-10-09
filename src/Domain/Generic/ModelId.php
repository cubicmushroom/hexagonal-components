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
 */
abstract class ModelId
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
     * @param $value
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
     * @param string|ModelId|ModelInterface $id
     *
     * @return bool
     */
    public function equals($id)
    {
        if ($id instanceof ModelId) {

            return ($id->getValue() === $this->value);
        } elseif (is_a($id, $this->getModelClass(), true)) {

            $model = $id;

            if (!$model instanceof ModelInterface) {
                throw new \RuntimeException(sprintf('Model class %s must implement ModelInterface', get_class($model)));
            }

            return ($this->equals($model->getId()->getValue()));
        }

        return ($this->getValue() === (string)$id);
    }


    // -----------------------------------------------------------------------------------------------------------------
    // Getters
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}