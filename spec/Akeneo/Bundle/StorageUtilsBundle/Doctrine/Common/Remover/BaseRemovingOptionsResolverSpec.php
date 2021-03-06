<?php

namespace spec\Akeneo\Bundle\StorageUtilsBundle\Doctrine\Common\Remover;

use PhpSpec\ObjectBehavior;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class BaseRemovingOptionsResolverSpec extends ObjectBehavior
{
    function it_a_removing_options_resolver()
    {
        $this->shouldHaveType('Akeneo\Component\StorageUtils\Remover\RemovingOptionsResolverInterface');
    }

    function it_resolves_single_remove_options()
    {
        $this
            ->resolveRemoveOptions(['flush' => true, 'flush_only_object' => true])
            ->shouldReturn(['flush' => true, 'flush_only_object' => true]);
    }

    function it_resolves_default_values_for_single_remove_options()
    {
        $this
            ->resolveRemoveOptions([])
            ->shouldReturn(['flush' => true, 'flush_only_object' => false]);
    }

    function it_resolves_bulk_remove_options()
    {
        $this
            ->resolveRemoveAllOptions(['flush' => true])
            ->shouldReturn(['flush' => true]);
    }

    function it_resolves_default_values_for_bulk_remove_options()
    {
        $this
            ->resolveRemoveAllOptions([])
            ->shouldReturn(['flush' => true]);
    }

    function it_throws_an_exception_when_resolve_unknown_saving_option()
    {
        $this
            ->shouldThrow(new InvalidOptionsException('The option "fake_option" does not exist. Known options are: "flush", "flush_only_object"'))
            ->duringResolveRemoveOptions(['fake_option' => true, 'flush' => false]);
    }
}
