<?php

namespace Spatie\TabularAssertions\PHPUnit;

use PHPUnit\Framework\TestCase;
use Spatie\TabularAssertions\Table;

/** @mixin TestCase */
trait TabularAssertions
{
    public function assertMatchesTable(string $expected, array $actual): void
    {
        [$expected, $actual] = Table::from($expected)->compare($actual);

        $this->assertSame($expected, $actual);
    }
}
