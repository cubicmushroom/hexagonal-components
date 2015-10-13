<?php

namespace spec\CubicMushroom\Hexagonal\Domain\Generic {

    use CubicMushroom\Hexagonal\Domain\Generic\Model;
    use CubicMushroom\Hexagonal\Domain\Generic\ModelId;
    use CubicMushroom\Hexagonal\Domain\Generic\ModelInterface;
    use CubicMushroom\Hexagonal\Domain\Generic\ModelTest;
    use CubicMushroom\Hexagonal\Exception\Domain\Generic\IdAlreadyAssignedException;
    use PhpSpec\Exception\Example\PendingException;
    use PhpSpec\ObjectBehavior;
    use Prophecy\Argument;

    class ModelTestSpec extends ObjectBehavior
    {
        const ID = 8198;

        /**
         * @var ModelId
         */
        protected $id;


        function let(ModelId $id)
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
        function it_should_allow_id_to_be_assigned_only_once(ModelId $id)
        {
            /** @noinspection PhpUndefinedMethodInspection */
            $this->assignId($id)->shouldReturnAnInstanceOf(Model::class);
            /** @noinspection PhpUndefinedMethodInspection */
            $this->id()->shouldReturn($id);

            $expectedException = new IdAlreadyAssignedException(sprintf('$id of value %s already assigned', self::ID));

            $this->shouldThrow($expectedException)->during('assignId', [$id]);
        }


        function it_should_check_the_id_type_for_the_extended_class()
        {
            throw new PendingException('Add check');
        }
    }
}

namespace CubicMushroom\Hexagonal\Domain\Generic {

    class ModelTest extends Model
    {
    }
}