<?php

namespace spec\CubicMushroom\Hexagonal\Command {

    use CubicMushroom\Hexagonal\Command\AbstractCommandHandler;
    use CubicMushroom\Hexagonal\Command\CommandHandlerInterface;
    use CubicMushroom\Hexagonal\Command\TestAbstractCommandHandler;
    use CubicMushroom\Hexagonal\Command\TestAbstractCommandHandlerFailedEvent;
    use CubicMushroom\Hexagonal\Command\TestAbstractCommandHandlerSucceededEvent;
    use CubicMushroom\Hexagonal\Command\TestCorrectCommand;
    use CubicMushroom\Hexagonal\Command\TestIncorrectCommand;
    use CubicMushroom\Hexagonal\Exception\Command\InvalidCommandException;
    use League\Event\EmitterInterface;
    use PhpSpec\ObjectBehavior;
    use Prophecy\Argument;
    use Psr\Log\LoggerInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    /**
     * Class TestAbstractCommandHandlerSpec
     *
     * @package CubicMushroom\Hexagonal
     *
     * @see     \CubicMushroom\Hexagonal\Command\AbstractCommandHandler
     */
    class TestAbstractCommandHandlerSpec extends ObjectBehavior
    {
        const TEST_CLASS = '\CubicMushroom\Hexagonal\Command\TestAbstractCommandHandler';


        /**
         * @uses AbstractCommandHandler::__construct()
         */
        function let(
            /** @noinspection PhpDocSignatureInspection */
            ValidatorInterface $validator,
            EmitterInterface $emitter
        ) {
            $this->beConstructedThrough('createBasic', [$validator, $emitter]);
        }


        /**
         * @uses AbstractCommandHandler::__construct()
         */
        function it_is_initializable()
        {
            $this->shouldHaveType(TestAbstractCommandHandler::class);
        }


        /**
         * @uses AbstractCommandHandler::__construct()
         */
        function it_should_implement_command_handler_interface()
        {
            /** @noinspection PhpUndefinedMethodInspection */
            $this->shouldBeAnInstanceOf(CommandHandlerInterface::class);
            /** @noinspection PhpUndefinedMethodInspection */
            $this->shouldBeAnInstanceOf(AbstractCommandHandler::class);
        }


        /**
         * @uses AbstractCommandHandler::handle()
         */
        function it_should_be_ok_with_its_own_command_type()
        {
            /** @noinspection PhpUndefinedMethodInspection */
            $this->handle(new TestCorrectCommand);
        }


        /**
         * @uses AbstractCommandHandler::handle()
         */
        function it_should_throw_an_exception_for_any_other_command_type()
        {
            /** @noinspection PhpUndefinedMethodInspection */
            $this->shouldThrow(InvalidCommandException::class)->during('handle', [new TestIncorrectCommand]);
        }


        /**
         * @uses AbstractCommandHandler::handle()
         */
        function it_should_log_incorrect_command_if_logger_available(
            /** @noinspection PhpDocSignatureInspection */
            ValidatorInterface $validator,
            EmitterInterface $emitter,
            LoggerInterface $logger
        ) {
            $this->beConstructedThrough('createWithLogger', [$validator, $emitter, $logger]);

            /** @noinspection PhpUndefinedMethodInspection */
            $this->shouldThrow(InvalidCommandException::class)->during('handle', [new TestIncorrectCommand]);

            /** @noinspection PhpUndefinedMethodInspection */
            $logger->error(Argument::containingString(ltrim(self::TEST_CLASS, '\\').' cannot handle commands of type '))
                   ->shouldHaveBeenCalled();
        }


        /**
         * @uses AbstractCommandHandler::handle()
         */
        function it_should_validate_the_command(
            /** @noinspection PhpDocSignatureInspection */
            ValidatorInterface $validator
        ) {
            $command = new TestCorrectCommand;

            /** @noinspection PhpUndefinedMethodInspection */
            $this->handle($command);

            /** @noinspection PhpUndefinedMethodInspection */
            $validator->validate($command)->shouldHaveBeenCalled();
        }


        /**
         * @uses AbstractCommandHandler::handle()
         */
        function it_should_call_the_handle_method()
        {
            $command = new TestCorrectCommand;

            /** @noinspection PhpUndefinedMethodInspection */
            $this->handle($command);

            /** @noinspection PhpUndefinedMethodInspection */
            $this->shouldHaveHandleBeenCalled();
        }


        /**
         * @uses AbstractCommandHandler::handle()
         */
        function it_should_emit_an_event_on_success(
            /** @noinspection PhpDocSignatureInspection */
            EmitterInterface $emitter
        ) {

            /** @noinspection PhpUndefinedMethodInspection */
            $this->handle(new TestCorrectCommand);

            /** @noinspection PhpUndefinedMethodInspection */
            $emitter->emit(Argument::type(TestAbstractCommandHandlerSucceededEvent::class))->shouldHaveBeenCalled();
        }


        /**
         * @uses AbstractCommandHandler::handle()
         */
        function it_should_emit_an_event_on_failure(
            /** @noinspection PhpDocSignatureInspection */
            TestCorrectCommand $command,
            ValidatorInterface $validator,
            EmitterInterface $emitter
        ) {
            $this->beConstructedThrough('createToFail', [$validator, $emitter]);

            // This tests check that this thrown exception matches the one thrown in
            // \CubicMushroom\Hexagonal\Command\TestAbstractCommandHandler::_handle() below
            /** @noinspection PhpUndefinedMethodInspection */
            $this->shouldThrow(new \Exception('I am supposed to fail for this test'))
                 ->during('handle', [$command]);

            /** @noinspection PhpUndefinedMethodInspection */
            $emitter->emit(Argument::type(TestAbstractCommandHandlerFailedEvent::class))->shouldHaveBeenCalled();
        }
    }
}

namespace CubicMushroom\Hexagonal\Command {

    use CubicMushroom\Hexagonal\Event\CommandFailedEventInterface;
    use CubicMushroom\Hexagonal\Event\CommandSucceededEventInterface;
    use League\Event\EmitterInterface;
    use League\Event\Event;
    use Psr\Log\LoggerInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    /**
     * Test class used to test the functionality of the AbstractCommandHandler class
     *
     * IMPORTANT: Do not implement any methods in this class, other than the abstract ones from AbstractCommandHandler
     *            The only excpetion to this is methods used to test the object's state under test
     *
     * @package CubicMushroom\Hexagonal
     */
    class TestAbstractCommandHandler extends AbstractCommandHandler
    {
        /**
         * Custom builder used to pass in $shouldFail flag
         *
         * @param ValidatorInterface $validator
         * @param EmitterInterface   $emitter
         *
         * @return self
         */
        public static function createToFail(
            ValidatorInterface $validator,
            EmitterInterface $emitter
        ) {
            /** @var self $handler */
            $handler = parent::createBasic($validator, $emitter);

            $handler->shouldFail = true;

            return $handler;
        }

        /**
         * Custom builder used to inject logger service
         *
         * @param ValidatorInterface $validator
         * @param EmitterInterface   $emitter
         * @param LoggerInterface    $logger
         *
         * @return self
         */
        public static function createWithLogger(
            ValidatorInterface $validator,
            EmitterInterface $emitter,
            LoggerInterface $logger
        ) {
            /** @var self $handler */
            $handler = parent::createBasic($validator, $emitter);

            $handler->logger = $logger;

            return $handler;
        }


        /**
         * @var bool
         */
        protected $shouldFail;


        /**
         * @var bool
         */
        protected $hasHandlerBeenCalled = false;


        /**
         * Should return the class of the command that the handler handles
         *
         * @return string
         */
        protected function getCommandClass()
        {
            return TestCorrectCommand::class;
        }


        /**
         *
         * @param CommandInterface $command
         *
         * @throws \Exception
         */
        protected function _handle(CommandInterface $command)
        {
            if ($this->shouldFail) {
                throw new \Exception('I am supposed to fail for this test');
            }
            
            $this->hasHandlerBeenCalled = true;
        }


        /**
         * @param CommandInterface $command
         *
         * @return CommandSucceededEventInterface
         */
        protected function getSuccessEvent(CommandInterface $command)
        {
            return new TestAbstractCommandHandlerSucceededEvent('TestAbstractCommandHandlerSuccess');
        }


        /**
         * @param \Exception $exception
         *
         * @return CommandFailedEventInterface
         */
        protected function getFailureEvent(\Exception $exception)
        {
            return new TestAbstractCommandHandlerFailedEvent('TestAbstractCommandHandlerFailure');
        }


        // -----------------------------------------------------------------------------------------------------------------
        // State test methods
        // -----------------------------------------------------------------------------------------------------------------

        /**
         * @return bool
         */
        public function hasHandleBeenCalled()
        {
            return $this->hasHandlerBeenCalled;
        }
    }

    class TestCorrectCommand implements CommandInterface
    {
    }

    class TestIncorrectCommand implements CommandInterface
    {
    }

    /**
     * Test class used to manipulate the success/failure of the TestAbstractCommandHandler handle() method
     *
     * @package CubicMushroom\Hexagonal
     */
    class HandlerResponseManager
    {
    }

    class TestAbstractCommandHandlerSucceededEvent extends Event implements CommandSucceededEventInterface
    {
    }

    class TestAbstractCommandHandlerFailedEvent extends Event implements CommandFailedEventInterface
    {
    }
}