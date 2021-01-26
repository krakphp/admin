<?php

namespace League\Plates;

final class PortalTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function appending_to_a_portal() {
        $portal = new Portal();

        $portal
            ->append('a')
            ->append('b');

        $this->assertEquals('ab', (string) $portal);
    }

    /** @test */
    public function prepending_a_portal() {
        $portal = new Portal();

        $portal
            ->prepend('a')
            ->prepend('b');

        $this->assertEquals('ba', (string) $portal);
    }

    /** @test */
    public function clearing_a_portal() {
        $portal = new Portal();

        $portal
            ->append('a')
            ->clear();

        $this->assertEquals('', (string) $portal);
    }

    /** @test */
    public function setting_to_a_portal() {
        $portal = new Portal();

        $portal->set('a', 'a')->set('a', 'b');

        $this->assertEquals('b', (string) $portal);
    }
}
