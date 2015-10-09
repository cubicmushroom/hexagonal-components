<?php

namespace spec\CubicMushroom\Hexagonal\Domain\Generic;

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

    function it_should_be_possible_to_construct_with_an_existing_value()
    {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->beConstructedWith('123');
    }
}