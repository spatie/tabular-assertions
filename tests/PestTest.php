<?php

$users = [
    ['id' => 20, 'name' => 'John Doe', 'email' => 'john@doe.com'],
    ['id' => 1245, 'name' => 'Jane Doe', 'email' => 'jane@doe.com'],
];


test('it compares a table', function () use ($users) {
    expect($users)->toMatchTable('
        |   id | name        | email           |
        |   20 | John Doe    | john@doe.com    |
        | 2344 | Johanna Doe | johanna@doe.com |
    ');
});
