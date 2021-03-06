<?php

namespace CubicMushroom\Hexagonal\Command;

use CubicMushroom\Hexagonal\Event\CommandFailedEventInterface;
use CubicMushroom\Hexagonal\Event\CommandSucceededEventInterface;
use League\Event\EmitterInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AbstractCommandHandler
 *
 * @package CubicMushroom\Hexagonal
 *
 * @see     \spec\CubicMushroom\Hexagonal\Command\TestAbstractCommandHandlerSpec
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
        $abstractCommandHandler->emitter   = $emitter;

        return $abstractCommandHandler;
    }

    // -----------------------------------------------------------------------------------------------------------------
    // Properties
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * If set, will be used to log exceptions to
     *
     * @var LoggerInterface
     */
    protected $logger;


    // -----------------------------------------------------------------------------------------------------------------
    // Constructor
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
            // @todo - Update this to return the succes event object, rather than using the getSuccessEvent() method
            $this->_handle($command);
        } catch (\Exception $exception) {

            // Log exception
            $commandClass = get_class($command);
            $this->logError(
                "Exception throw while handling {$commandClass} command... {$exception->getMessage()}\n",
                $exception
            );

            // Fire failure event
            $this->emit($this->getFailureEvent($exception));

            throw $exception;
        }

        $event = $this->getSuccessEvent($command);
        $this->emit($event);
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


    /**
     * Logs an error, if the error logger is available
     *
     * @param string     $errorMessage
     * @param \Exception $exception
     */
    function logError($errorMessage, \Exception $exception)
    {
        if ($this->logger instanceof LoggerInterface) {
            $this->logger->error($errorMessage . "\n" . $exception->getTraceAsString());
        }
    }


}
