<?php

namespace spec\CubicMushroom\Hexagonal\Domain\User;

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
    function it_is_initializable()
    {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->beConstructedWith(123);
        $this->shouldHaveType(UserId::class);
    }


    /**
     * @uses UserId::getId()
     */
    function it_can_be_initialised_with_an_integer()
    {
        $id = 123;

        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->beConstructedWith($id);

        /** @noinspection PhpUndefinedMethodInspection */
        $this->getId()->shouldReturn($id);
    }


    function it_can_be_initialised_with_a_string()
    {
        $id = 'abc';

        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->beConstructedWith($id);

        /** @noinspection PhpUndefinedMethodInspection */
        $this->getId()->shouldReturn($id);
    }
}
