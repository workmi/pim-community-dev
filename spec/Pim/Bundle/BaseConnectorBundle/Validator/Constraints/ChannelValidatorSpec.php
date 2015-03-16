<?php

namespace spec\Pim\Bundle\BaseConnectorBundle\Validator\Constraints;

use PhpSpec\ObjectBehavior;
use Pim\Bundle\CatalogBundle\Manager\ChannelManager;
use Prophecy\Argument;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

class ChannelValidatorSpec extends ObjectBehavior
{
    function let(ChannelManager $channelManager)
    {
        $this->beConstructedWith($channelManager);
    }

    function it_is_a_choice_validator()
    {
        $this->shouldHaveType('\Symfony\Component\Validator\Constraints\ChoiceValidator');
    }

    function it_throws_an_exception_if_there_is_no_channel_choices($channelManager, Constraint $constraint)
    {
        $channelManager->getChannelChoices()->willReturn([]);

        $this
            ->shouldThrow(new ConstraintDefinitionException('No channel is set in the application'))
            ->duringValidate(Argument::any(), $constraint);
    }
}
