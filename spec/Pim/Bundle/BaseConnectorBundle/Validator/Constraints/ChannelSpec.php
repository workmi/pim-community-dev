<?php

namespace spec\Pim\Bundle\BaseConnectorBundle\Validator\Constraints;

use PhpSpec\ObjectBehavior;

class ChannelSpec extends ObjectBehavior
{
    function it_gives_the_good_validator_name()
    {
        $this->validatedBy()->shouldReturn('channel_validator');
    }
}
