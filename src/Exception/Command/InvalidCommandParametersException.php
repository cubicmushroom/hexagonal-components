<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 06/10/15
 * Time: 12:59
 */

namespace CubicMushroom\Hexagonal\Exception\Command;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class InvalidCommandArgumentsException
 *
 * @package CubicMushroom\Hexagonal
 */
class InvalidCommandParametersException extends AbstractCommandException
{
    /**
     * @var ConstraintViolationListInterface
     */
    protected $violations;


    /**
     * InvalidCommandArgumentsException constructor.
     *
     * @param ConstraintViolationListInterface $violations
     * @param string                           $message
     * @param int                              $code
     * @param \Exception                       $previous
     */
    public function __construct(
        ConstraintViolationListInterface $violations,
        $message = "",
        $code = 0,
        \Exception $previous = null
    ) {
        $this->violations = $violations;

        parent::__construct($message, $code, $previous);
    }


}