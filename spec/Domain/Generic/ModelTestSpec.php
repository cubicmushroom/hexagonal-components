<?php

namespace spec\CubicMushroom\Hexagonal\Domain\Generic {

    use CubicMushroom\Hexagonal\Domain\Generic\CorrectId;
    use CubicMushroom\Hexagonal\Domain\Generic\Model;
    use CubicMushroom\Hexagonal\Domain\Generic\ModelId;
    use CubicMushroom\Hexagonal\Domain\Generic\ModelInterface;
    use CubicMushroom\Hexagonal\Domain\Generic\ModelTest;
    use CubicMushroom\Hexagonal\Domain\Generic\WrongId;
    use CubicMushroom\Hexagonal\Exception\Domain\Generic\IdAlreadyAssignedException;
    use CubicMushroom\Hexagonal\Exception\Domain\Generic\InvalidIdException;
    use PhpSpec\ObjectBehavior;
    use Prophecy\Argument;

    class ModelTestSpec extends ObjectBehavior
    {
        const ID = 8198;

        /**
         * @var ModelId
         */
        protected $id;


        function let(CorrectId $id)
        {
            $id->getValue()->willReturn(self::ID);
        }


        function it_is_initializable()
        {
            $this->shouldHaveType(ModelTest::class);
        }


        function it_should_implement_model_interface()
        {
            /** @noinspection PhpUndefinedMethodInspection */
            $this->shouldbeAnInstanceOf(ModelInterface::class);
        }


        /**
         * @uses Model::assignId()
         * @uses Model::id()
         */
        function it_should_allow_id_to_be_assigned_only_once(
            /** @noinspection PhpDocSignatureInspection */
            CorrectId $id
        ) {
            /** @noinspection PhpUndefinedMethodInspection */
            $this->assignId($id)->shouldReturnAnInstanceOf(Model::class);
            /** @noinspection PhpUndefinedMethodInspection */
            $this->id()->shouldReturn($id);

            $expectedException = new IdAlreadyAssignedException(sprintf('$id of value %s already assigned', self::ID));

            $this->shouldThrow($expectedException)->during('assignId', [$id]);
        }


        /**
         * @uses Model::assignId()
         */
        function it_should_check_the_id_type_for_the_extended_class(
            /** @noinspection PhpDocSignatureInspection */
            WrongId $wrongId
        ) {
            $this->shouldThrow(InvalidIdException::class)->during('assignId', [$wrongId]);
        }
    }
}

namespace CubicMushroom\Hexagonal\Domain\Generic {

    class ModelTest extends Model
    {
        /**
         * Should return the class of the model's $id field value object
         *
         * @return string
         */
        protected function getIdClass()
        {
            return CorrectId::class;
        }
    }

    class CorrectId extends ModelId
    {
    }

    class WrongId extends ModelId
    {
    }
}