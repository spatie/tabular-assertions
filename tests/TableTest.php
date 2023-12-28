<?php

use Spatie\TabularAssertions\Table;

test('it parses an expectation with a single column and a single row', function () {
    $expected = Table::from('
        | first_name |
        | John       |
    ');

    expect($expected->columns)->toHaveCount(1);

    expect($expected->columns[0]->name)->toBe('first_name');
    expect($expected->columns[0]->width)->toBe(10);
    expect($expected->columns[0]->numeric)->toBeFalse();

    expect($expected->rows)->toHaveCount(1);

    expect($expected->rows[0])->toHaveCount(1);
    expect($expected->rows[0][0]->data)->toBe('John');
});

test('it parses an expectation with two columns and two rows', function () {
    $expected = Table::from('
        | first_name | last_name |
        | John       | Doe       |
        | Jane       | Doe       |
    ');

    expect($expected->columns)->toHaveCount(2);

    expect($expected->columns[1]->name)->toBe('last_name');
    expect($expected->columns[1]->width)->toBe(9);
    expect($expected->columns[1]->numeric)->toBeFalse();

    expect($expected->columns[0]->name)->toBe('first_name');
    expect($expected->columns[0]->width)->toBe(10);
    expect($expected->columns[0]->numeric)->toBeFalse();

    expect($expected->rows)->toHaveCount(2);

    expect($expected->rows[0])->toHaveCount(2);
    expect($expected->rows[0][0]->data)->toBe('John');
    expect($expected->rows[0][1]->data)->toBe('Doe');

    expect($expected->rows[1])->toHaveCount(2);
    expect($expected->rows[1][0]->data)->toBe('Jane');
    expect($expected->rows[1][1]->data)->toBe('Doe');
});

test('it sizes the column based on the data', function () {
    $expected = Table::from('
        | name      |
        | Sebastian |
    ');

    expect($expected->columns)->toHaveCount(1);

    expect($expected->columns[0]->width)->toBe(9);
});

test('it recognizes left-aligned numeric data', function () {
    $expected = Table::from('
        | name      |   id | notes |
        | Sebastian | 1023 | test  |
        | Freek     | 2342 | 42    |
    ');

    expect($expected->columns)->toHaveCount(3);

    expect($expected->columns[0]->numeric)->toBeFalse();
    expect($expected->columns[1]->numeric)->toBeTrue();
    expect($expected->columns[2]->numeric)->toBeFalse();
});

test('it recognizes left-aligned numeric data in an empty dataset', function () {
    $expected = Table::from('
        | name      |   id | notes |
    ');

    expect($expected->columns)->toHaveCount(3);

    expect($expected->columns[0]->numeric)->toBeFalse();
    expect($expected->columns[1]->numeric)->toBeTrue();
    expect($expected->columns[2]->numeric)->toBeFalse();
});
