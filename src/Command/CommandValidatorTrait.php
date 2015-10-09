<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 06/10/15
 * Time: 13:15
 */

namespace CubicMushroom\Hexagonal\Command;

use CubicMushroom\Hexagonal\Exception\Command\InvalidCommandException;
use CubicMushroom\Hexagonal\Exception\Command\InvalidCommandParametersException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Provides helper methods to Command handlers
 *
 * @package CubicMushroom\Hexagonal
 */
trait CommandValidatorTrait
{
    // -----------------------------------------------------------------------------------------------------------------
    // Properties
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * @var ValidatorInterface
     */
    protected $validator;


    // -----------------------------------------------------------------------------------------------------------------
    // Abstract methods
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * Should return the class name of the class this handler handles
     *
     * @return string
     */
    abstract protected function getCommandClass();


    // -----------------------------------------------------------------------------------------------------------------
    // Command validations
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * @param CommandInterface $command
     *
     * @throws InvalidCommandException if the command is not of the correct type
     */
    protected function checkCommandType(CommandInterface $command)
    {
        $commandClass = $this->getCommandClass();
        if (!is_a($command, $commandClass, true)) {
            $handlerClass = get_class($this);
            throw new InvalidCommandException(
                "Command handler '$handlerClass' should only be registered to handle '$commandClass' commands"
            );
        }
    }


    /**
     * @param CommandInterface $command
     *
     * @throws InvalidCommandException if self::checkCommandType() throws it
     * @throws \RuntimeException if validator is not set on the object
     * @throws InvalidCommandParametersException if the command object does not validate
     */
    protected function validateCommand(CommandInterface $command)
    {
        $this->checkCommandType($command);

        if (!$this->validator instanceof ValidatorInterface) {
            throw new \RuntimeException('Validator is not available');
        }

        $violationsList = $this->validator->validate($command);

        if (count($violationsList) > 0) {
            throw new InvalidCommandParametersException($violationsList);
        }
    }


    // -----------------------------------------------------------------------------------------------------------------
    // Getters and Setters
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * @param ValidatorInterface $validator
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
}