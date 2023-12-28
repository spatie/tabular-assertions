<?php

use Spatie\TabularAssertions\Column;
use Spatie\TabularAssertions\Serialize;

$serialize = new Serialize();

test('it serializes data in a single column', function () use ($serialize) {
    $actual = $serialize(
        [new Column('first_name')],
        [['first_name' => 'John']],
    );

    expect($actual->columns)->toHaveCount(1);

    expect($actual->columns[0]->name)->toBe('first_name');
    expect($actual->columns[0]->width)->toBe(10);
    expect($actual->columns[0]->numeric)->toBeFalse();

    expect($actual->rows)->toHaveCount(1);

    expect($actual->rows[0])->toHaveCount(1);
    expect($actual->rows[0][0]->data)->toBe('John');
});

test('it parses an expectation with two columns and two rows', function () use ($serialize) {
    $actual = $serialize(
        [new Column('first_name'), new Column('last_name')],
        [
            ['first_name' => 'John', 'last_name' => 'Doe'],
            ['first_name' => 'Jane', 'last_name' => 'Doe'],
        ],
    );

    expect($actual->columns)->toHaveCount(2);

    expect($actual->columns[0]->name)->toBe('first_name');
    expect($actual->columns[0]->width)->toBe(10);
    expect($actual->columns[0]->numeric)->toBeFalse();

    expect($actual->columns[1]->name)->toBe('last_name');
    expect($actual->columns[1]->width)->toBe(9);
    expect($actual->columns[1]->numeric)->toBeFalse();

    expect($actual->rows)->toHaveCount(2);

    expect($actual->rows[0])->toHaveCount(2);
    expect($actual->rows[0][0]->data)->toBe('John');
    expect($actual->rows[0][1]->data)->toBe('Doe');

    expect($actual->rows[1])->toHaveCount(2);
    expect($actual->rows[1][0]->data)->toBe('Jane');
    expect($actual->rows[1][1]->data)->toBe('Doe');
});

test('it expands column widths when a row contains longer data', function () use ($serialize) {
    $actual = $serialize(
        [new Column('name')],
        [['name' => 'John Doe']],
    );

    expect($actual->columns)->toHaveCount(1);
    expect($actual->columns[0]->name)->toBe('name');
    expect($actual->columns[0]->width)->toBe(8);
});
