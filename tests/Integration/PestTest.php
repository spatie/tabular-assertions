<?php

$users = [
    ['id' => 20, 'name' => 'John Doe', 'email' => 'john@doe.com'],
    ['id' => 1245, 'name' => 'Jane Doe', 'email' => 'jane@doe.com'],
];

test('it compares a table', function () use ($users) {
    expect($users)->toMatchTable('
        |   id | name        | email           |
        |   20 | John Doe    | john@doe.com    |
        | 1245 | Jane Doe    | jane@doe.com    |
    ');
});


test('it fails when the table does not match', function () use ($users) {
    expect($users)->toMatchTable('
        |   id | name        | email           |
        |   20 | Wrong name  | john@doe.com    |
        | 1245 | Jane Doe    | jane@doe.com    |
    ');
})->fails();
