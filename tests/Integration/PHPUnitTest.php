<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use Spatie\TabularAssertions\PHPUnit\TabularAssertions;

class PHPUnitTest extends TestCase
{
    use TabularAssertions;

    public function test_it_compares_a_table(): void
    {
        $this->assertMatchesTable('
            |   id | name        | email           |
            |   20 | John Doe    | john@doe.com    |
            | 1245 | Jane Doe    | jane@doe.com    |
        ', [
            ['id' => 20, 'name' => 'John Doe', 'email' => 'john@doe.com'],
            ['id' => 1245, 'name' => 'Jane Doe', 'email' => 'jane@doe.com'],
        ]);
    }
}
