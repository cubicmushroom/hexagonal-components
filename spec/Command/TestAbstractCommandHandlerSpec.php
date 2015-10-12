<?php

namespace spec\CubicMushroom\Hexagonal\Command {

    use CubicMushroom\Hexagonal\Command\AbstractCommandHandler;
    use CubicMushroom\Hexagonal\Command\CommandHandlerInterface;
    use CubicMushroom\Hexagonal\Command\CommandInterface;
    use CubicMushroom\Hexagonal\Command\TestAbstractCommandHandler;
    use CubicMushroom\Hexagonal\Command\TestAbstractCommandHandlerFailedEvent;
    use CubicMushroom\Hexagonal\Command\TestAbstractCommandHandlerSucceededEvent;
    use League\Event\EmitterInterface;
    use PhpSpec\ObjectBehavior;
    use Prophecy\Argument;
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
        /**
         * @uses AbstractCommandHandler::__construct()
         */
        function let(
            /** @noinspection PhpDocSignatureInspection */
            ValidatorInterface $validator,
            EmitterInterface $emitter
        ) {
            $this->beConstructedThrough('createWithFailureFlag', [$validator, $emitter, false]);
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
        function it_should_validate_the_command(
            /** @noinspection PhpDocSignatureInspection */
            CommandInterface $command,
            ValidatorInterface $validator
        ) {
            /** @noinspection PhpUndefinedMethodInspection */
            $this->handle($command);

            /** @noinspection PhpUndefinedMethodInspection */
            $validator->validate($command)->shouldHaveBeenCalled();
        }


        /**
         * @uses AbstractCommandHandler::handle()
         */
        function it_should_emit_an_event_on_success(
            /** @noinspection PhpDocSignatureInspection */
            CommandInterface $command,
            EmitterInterface $emitter
        ) {

            /** @noinspection PhpUndefinedMethodInspection */
            $this->handle($command);

            /** @noinspection PhpUndefinedMethodInspection */
            $emitter->emit(Argument::type(TestAbstractCommandHandlerSucceededEvent::class))->shouldHaveBeenCalled();
        }


        /**
         * @uses AbstractCommandHandler::handle()
         */
        function it_should_emit_an_event_on_failure(
            /** @noinspection PhpDocSignatureInspection */
            CommandInterface $command,
            ValidatorInterface $validator,
            EmitterInterface $emitter
        ) {
            $this->beConstructedThrough('createWithFailureFlag', [$validator, $emitter, true]);

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
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    /**
     * Test class used to test the functionality of the AbstractCommandHandler class
     *
     * IMPORTANT: Do not implement any methods in this class, other than the abstract ones from AbstractCommandHandler
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
         * @param bool               $shouldFail
         *
         * @return self
         */
        public static function createWithFailureFlag(
            ValidatorInterface $validator,
            EmitterInterface $emitter,
            $shouldFail
        ) {
            /** @var self $handler */
            $handler = parent::createBasic($validator, $emitter);

            $handler->shouldFail = $shouldFail;

            return $handler;
        }


        /**
         * @var bool
         */
        protected $shouldFail;


        /**
         * @param CommandInterface $command
         *
         * @return CommandSucceededEventInterface
         */
        protected function getSuccessEvent($command)
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


        /**
         *
         * @param $command
         *
         * @throws \Exception
         */
        protected function _handle($command)
        {
            if ($this->shouldFail) {
                throw new \Exception('I am supposed to fail for this test');
            }
        }
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