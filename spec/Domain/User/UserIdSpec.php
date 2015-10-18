<?php

namespace spec\CubicMushroom\Hexagonal\Domain\User;

use CubicMushroom\Hexagonal\Domain\Generic\ModelId;
use CubicMushroom\Hexagonal\Domain\User\UserId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class UserIdSpec
 *
 * @package spec\CubicMushroom\Hexagonal\Domain\User
 *
 * @see     \CubicMushroom\Hexagonal\Domain\User\UserId
 */
class UserIdSpec extends ObjectBehavior
{
    const NUMBER_ID = 123;


    const STRING_ID = 'abc';


    function let()
    {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->beConstructedWith(self::NUMBER_ID);
    }


    function it_is_initializable()
    {
        $this->shouldHaveType(UserId::class);
    }


    function it_extends_model_id()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->shouldBeAnInstanceOf(ModelId::class);
    }


    /**
     * @uses UserId::getId()
     */
    function it_can_be_initialised_with_an_integer()
    {

        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->beConstructedWith(self::NUMBER_ID);

        /** @noinspection PhpUndefinedMethodInspection */
        $this->getId()->shouldReturn(self::NUMBER_ID);
    }


    function it_can_be_initialised_with_a_string()
    {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->beConstructedWith(self::STRING_ID);

        /** @noinspection PhpUndefinedMethodInspection */
        $this->getId()->shouldReturn(self::STRING_ID);
    }


    function it_can_be_converted_to_a_string()
    {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->beConstructedWith(self::NUMBER_ID);

        /** @noinspection PhpUndefinedMethodInspection */
        $this->__toString()->shouldReturn((string) self::NUMBER_ID);
    }
}
