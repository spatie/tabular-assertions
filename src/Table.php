<?php

namespace Spatie\TabularAssertions;

use ParseError;

class Table
{
    /**
     * @param  Column[]  $columns
     * @param  Cell[][]  $rows
     */
    private function __construct(
        public array $columns,
        public array $rows,
    ) {
    }

    public static function from(string $input): self
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

        return new self($columns, $rows);
    }

    /**
     * @param mixed[][] $data
     * @return string[]
     */
    public function compare(array $data): array
    {
        $rows = array_map(function (array $row) {
            return array_map(function (Column $column, mixed $row) {
                return $column->cell($row);
            }, $this->columns, $row);
        }, $data);

        return [
            $this->serialize($this->rows),
            $this->serialize($rows),
        ];
    }

    /** @param Cell[][] $rows */
    private function serialize(array $rows): string
    {
        $data = [[]];

        foreach ($this->columns as $columnIndex => $column) {
            $data[0][$columnIndex] = str_pad($column->name, $column->width);

            foreach ($rows as $rowIndex => $row) {
                $data[$rowIndex + 1][$columnIndex] = str_pad($row[$columnIndex]->data, $column->width);
            }
        }

        return PHP_EOL . implode(PHP_EOL, array_map(function (array $row) {
            return '| ' . implode(' | ', $row) . ' |';
        }, $data)) . PHP_EOL;
    }
}
