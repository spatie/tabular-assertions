<?php

test('it compares a table', function () {
    expect([
        ['name' => 'John Doe', 'email' => 'john@doe.com'],
        ['name' => 'Johanna Doe', 'email' => 'johanna@doe.com'],
    ])->toMatchTable('
        | name        | email           |
        | John Doe    | john@doe.com    |
        | Johanna Doe | johanna@doe.com |
    ');
});
