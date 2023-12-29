<?php

use Spatie\TabularAssertions\Align;
use Spatie\TabularAssertions\Table;

function dedent(string $string): string
{
    return preg_replace('/^ +/m', '', $string);
}

test('it parses an expectation with a single column and a single row', function () {
    $expected = Table::from('
        | first_name |
        | John       |
    ');

    expect($expected->columns)->toHaveCount(1);

    expect($expected->columns[0]->name)->toBe('first_name');
    expect($expected->columns[0]->width)->toBe(10);
    expect($expected->columns[0]->align)->toBe(Align::Left);

    expect($expected->rows)->toHaveCount(1);

    expect($expected->rows[0])->toHaveCount(1);
    expect($expected->rows[0][0])->toBe('John');
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
    expect($expected->columns[1]->align)->toBe(Align::Left);

    expect($expected->columns[0]->name)->toBe('first_name');
    expect($expected->columns[0]->width)->toBe(10);
    expect($expected->columns[0]->align)->toBe(Align::Left);

    expect($expected->rows)->toHaveCount(2);

    expect($expected->rows[0])->toHaveCount(2);
    expect($expected->rows[0][0])->toBe('John');
    expect($expected->rows[0][1])->toBe('Doe');

    expect($expected->rows[1])->toHaveCount(2);
    expect($expected->rows[1][0])->toBe('Jane');
    expect($expected->rows[1][1])->toBe('Doe');
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

    expect($expected->columns[0]->align)->toBe(Align::Left);
    expect($expected->columns[1]->align)->toBe(Align::Right);
    expect($expected->columns[2]->align)->toBe(Align::Left);
});

test('it recognizes left-aligned numeric data in an empty dataset', function () {
    $expected = Table::from('
        | name      |   id | notes |
    ');

    expect($expected->columns)->toHaveCount(3);

    expect($expected->columns[0]->align)->toBe(Align::Left);
    expect($expected->columns[1]->align)->toBe(Align::Right);
    expect($expected->columns[2]->align)->toBe(Align::Left);
});

test('it uses an empty string when data is missing a key', function () {
    $expected = Table::from('
        | name      |
        | Sebastian |
    ');

    [$expected, $actual] = $expected->compare([['first_name' => 'Sebastian']]);

    expect($expected)->not->toBe($actual);
    expect($actual)->toBe(dedent('
        | name      |
        |           |
    '));
});

test('it strips out pipes from the dataset', function () {
    $expected = Table::from('
        | name    |
        | foo-bar |
    ');

    [$expected, $actual] = $expected->compare([['name' => 'foo|bar']]);

    expect($expected)->toBe($actual);
    expect($actual)->toBe(dedent('
        | name    |
        | foo-bar |
    '));
});

test('it supports dynamic values', function () {
    $expected = Table::from('
        | name      | #team_id | #job_id |
        | Sebastian |       #1 |      #1 |
        | Freek     |       #1 |      #1 |
        | Wouter    |       #1 |      #2 |
    ');

    [$expected, $actual] = $expected->compare([
        ['name' => 'Sebastian', 'team_id' => 453, 'job_id' => 524],
        ['name' => 'Freek', 'team_id' => 453, 'job_id' => 524],
        ['name' => 'Wouter', 'team_id' => 453, 'job_id' => 1243],
    ]);

    expect($expected)->toBe($actual);
    expect($actual)->toBe(dedent('
        | name      | #team_id | #job_id |
        | Sebastian |       #1 |      #1 |
        | Freek     |       #1 |      #1 |
        | Wouter    |       #1 |      #2 |
    '));
});
