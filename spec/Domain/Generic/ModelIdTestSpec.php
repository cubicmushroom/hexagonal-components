<?php

namespace spec\CubicMushroom\Hexagonal\Domain\Generic {

    use CubicMushroom\Hexagonal\Domain\Generic\ModelIdInterface;
    use PhpSpec\Exception\Example\ErrorException;
    use PhpSpec\ObjectBehavior;
    use Prophecy\Argument;

    /**
     * Class ModelIdTestSpec
     *
     * @package CubicMushroom\Hexagonal
     *
     * @see     \CubicMushroom\Hexagonal\Domain\Generic\ModelIdTest
     */
    class ModelIdTestSpec extends ObjectBehavior
    {
        const ID = '123';


        function let()
        {
            /** @noinspection PhpMethodParametersCountMismatchInspection */
            $this->beConstructedWith(self::ID);
        }

        function it_should_implement_model_id_interface()
        {
            /** @noinspection PhpUndefinedMethodInspection */
            $this->shouldBeAnInstanceOf(ModelIdInterface::class);
        }


        function it_should_not_be_possible_to_construct_without_a_value()
        {
            $this->shouldThrow(ErrorException::class)->during('__construct', []);
        }


        function it_should_be_convertable_to_a_string()
        {
            /** @noinspection PhpUndefinedMethodInspection */
            $this->__toString()->shouldReturn(self::ID);
        }
    }
}

namespace CubicMushroom\Hexagonal\Domain\Generic {

    /**
     * Class ModelIdTest
     *
     * @package CubicMushroom\Hexagonal
     *
     * @see     \spec\CubicMushroom\Hexagonal\Domain\Generic\ModelIdTestSpec
     */
    class ModelIdTest extends ModelId
    {
    }
}