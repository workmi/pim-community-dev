<?php

namespace spec\Pim\Bundle\BaseConnectorBundle\Validator\Import;

use PhpSpec\ObjectBehavior;

class SkipImportValidatorSpec extends ObjectBehavior
{
    function it_skips_an_import_validation($object)
    {
        $this->validate($object, [], [])->shouldReturn([]);
    }
}
