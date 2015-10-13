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

        function it_should_implement_model_id_interface()
        {
            $this->beAnInstanceOf(ModelIdInterface::class);
        }

        function it_should_be_possible_to_construct_with_an_existing_value()
        {
            /** @noinspection PhpMethodParametersCountMismatchInspection */
            $this->beConstructedWith('123');
        }


        function it_should_not_be_possible_to_construct_withuit_a_value()
        {
            $this->shouldThrow(ErrorException::class)->during('__construct', []);
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