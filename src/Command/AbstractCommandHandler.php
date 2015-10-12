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
    use CommandValidatorTrait;
    use EventHelperTrait;

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
     * Use factory methods to create
     */
    protected function __construct()
    {
    }


    /**
     * Processes the associated command
     *
     * @param CommandInterface $command
     *
     * @return void
     *
     * @throws \Exception if _handler() throws an error
     */
    final public function handle(CommandInterface $command)
    {
        $this->validateCommand($command);

        try {
            $this->_handle($command);
        } catch (\Exception $exception) {
            $failureEvent = $this->getFailureEvent($exception);
            $this->emit($failureEvent);
            throw $exception;
        }

        $event = $this->getSuccessEvent($command);
        $this->emit($event);
    }


    protected function checkCommandType(CommandInterface $command)
    {
        $commandClass = $this->getCommandClass();

        if (!get_class($command) !== $commandClass) {
            throw new InvalidCommandException("Command is not of '{$commandClass}' class");
        }
    }


    /**
     * @param CommandInterface $command
     */
    abstract protected function _handle(CommandInterface $command);


    /**
     * @param CommandInterface $command
     *
     * @return CommandSucceededEventInterface
     */
    abstract protected function getSuccessEvent(CommandInterface $command);


    /**
     * @param \Exception $exception
     *
     * @return CommandFailedEventInterface
     */
    abstract protected function getFailureEvent(\Exception $exception);
}
