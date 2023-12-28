<?php

namespace Spatie\TabularAssertions;

use ParseError;

class Parse
{
    public function __invoke(string $input): Table
    {
        $input = (string) preg_replace('/^\s+/m', '', trim($input));

        $data = explode("\n", $input);
        $header = array_shift($data);

        $columns = array_filter(array_map(function (string $header) {
            return new Column(trim($header));
        }, explode('|', trim($header, '|'))));

        $rows = array_map(function (string $row) use ($columns) {
            $cells = array_map(trim(...), explode('|', trim($row, '|')));

            if (count($cells) !== count($columns)) {
                throw new ParseError('Invalid input');
            }

            return array_map(function (string $cell, Column $column) {
                return $column->cell(trim($cell));
            }, explode('|', trim($row, '|')), $columns);
        }, $data);

        return new Table($columns, $rows);
    }
}
