<?php

namespace CubicMushroom\Hexagonal\Command;

use CubicMushroom\Hexagonal\Event\CommandFailedEventInterface;
use CubicMushroom\Hexagonal\Event\CommandSucceededEventInterface;
use CubicMushroom\Hexagonal\Exception\Command\InvalidCommandException;
use League\Event\EmitterInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AbstractCommandHandler
 *
 * @package CubicMushroom\Hexagonal
 *
 * @see \spec\CubicMushroom\Hexagonal\Command\AbstractCommandHandlerSpec
 */
abstract class AbstractCommandHandler implements CommandHandlerInterface
{
    // -----------------------------------------------------------------------------------------------------------------
    // Static factory methods
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * @param ValidatorInterface $validator
     * @param EmitterInterface   $emitter
     *
     * @return static
     */
    public static function createBasic(ValidatorInterface $validator, EmitterInterface $emitter)
    {
        $abstractCommandHandler = new static();

        $abstractCommandHandler->validator = $validator;
        $abstractCommandHandler->emitter = $emitter;

        return $abstractCommandHandler;
    }

    // -----------------------------------------------------------------------------------------------------------------
    // Properties
    // -----------------------------------------------------------------------------------------------------------------
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var EmitterInterface
     */
    private $emitter;


    /**
     * Use factory methods to create
     */
    private function __construct()
    {
    }


    /**
     * Processes the associated command
     *
     * @param CommandInterface $command
     *
     * @throws \Exception if _handler() throws an error
     */
    final public function handle(CommandInterface $command)
    {
        $this->checkCommandType($command);

        $this->validator->validate($command);

        try {
            $this->_handle($command);
        } catch (\Exception $exception) {
            $failureEvent = $this->getFailureEvent($exception);
            $this->emitter->emit($failureEvent);
            throw $exception;
        }

        $event = $this->getSuccessEvent($command);

        $this->emitter->emit($event);
    }


    protected function checkCommandType(CommandInterface $command)
    {
        $commandClass = $this->getCommandClass();

        if (!get_class($command) !== $commandClass) {
            throw new InvalidCommandException("Command is not of '{$commandClass}' class");
        }
    }


    /**
     * Should return the class of the command that the handler handles
     *
     * @return string
     */
    abstract protected function getCommandClass();


    /**
     * @param $command
     */
    abstract protected function _handle($command);


    /**
     * @param CommandInterface $command
     *
     * @return CommandSucceededEventInterface
     */
    abstract protected function getSuccessEvent($command);


    /**
     * @param \Exception $exception
     *
     * @return CommandFailedEventInterface
     */
    abstract protected function getFailureEvent(\Exception $exception);
}
