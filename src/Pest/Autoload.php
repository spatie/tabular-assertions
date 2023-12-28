<?php

use Spatie\TabularAssertions\Table;

if (class_exists(Pest\Plugin::class)) {
    expect()->extend('toMatchTable', function (string $expected) {
        [$expected, $actual] = Table::from($expected)->compare($this->value);

        expect($actual)->toBe($expected);
    });
}
