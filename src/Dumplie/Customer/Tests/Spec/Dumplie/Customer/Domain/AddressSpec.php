<?php

namespace Spec\Dumplie\Customer\Domain;

use Dumplie\Customer\Domain\Exception\InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AddressSpec extends ObjectBehavior
{
    function it_can_be_casted_to_string()
    {
        $this->beConstructedWith(
            'Norbert Orzechowicz',
            'ul. Floriańska 15',
            '30-300',
            'Kraków',
            'PL'
        );

        $this->__toString()->shouldReturn('Norbert Orzechowicz, 30-300 Kraków, ul. Floriańska 15, PL');
    }

    function it_throws_exception_for_invalid_country_iso2_code()
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', [
                'Norbert Orzechowicz',
                'ul. Floriańska 15',
                '30-300',
                'Kraków',
                'POL'
            ]);
    }

    function it_cant_have_empty_name()
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', [
                '',
                'ul. Floriańska 15',
                '30-300',
                'Kraków',
                'PL'
            ]);
    }

    function it_cant_have_empty_street()
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', [
                'Norbert Orzechowicz',
                '',
                '30-300',
                'Kraków',
                'PL'
            ]);
    }

    function it_cant_have_empty_post_code()
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', [
                'Norbert Orzechowicz',
                'ul. Floriańska 15',
                '',
                'Kraków',
                'PL'
            ]);
    }

    function it_cant_have_empty_city()
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', [
                'Norbert Orzechowicz',
                'ul. Floriańska 15',
                '30-300',
                '',
                'PL'
            ]);
    }

    function it_cant_have_empty_country_iso2_code()
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', [
                'Norbert Orzechowicz',
                'ul. Floriańska 15',
                '30-300',
                'Kraków',
                ''
            ]);
    }
}
