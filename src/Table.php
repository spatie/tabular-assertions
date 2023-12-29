<?php

namespace Spatie\TabularAssertions;

use ParseError;

class Table
{
    /**
     * @param  Column[]  $columns
     * @param  string[][]  $rows
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

        $columns = array_filter(array_map(function (string $header) use ($data) {
            // When there's data, assume the column is numeric as the data will determine how it is aligned later,
            // as _all_ data must be numeric.
            $alignRight = count($data) > 0 || preg_match('/^\s\s+/', $header);

            return Column::create($header, $alignRight ? Align::Right : Align::Left);
        }, explode('|', trim($header, '|'))));

        $rows = array_map(function (string $row) use ($columns) {
            $cells = array_map(trim(...), explode('|', trim($row, '|')));

            if (count($cells) !== count($columns)) {
                throw new ParseError('Invalid input');
            }

            return array_map(function (string $cell, Column $column) {
                if (! preg_match('/^(\s|\d|-|_|#)*$/', $cell)) {
                    $column->align = Align::Left;
                }

                return $column->cell($cell);
            }, explode('|', trim($row, '|')), $columns);
        }, $data);

        return new self($columns, $rows);
    }

    /**
     * @param  mixed[][]  $data
     * @return string[]
     */
    public function compare(array $data): array
    {
        $rows = array_map(function (array $row) {
            return array_map(function (Column $column) use ($row) {
                return $column->cell($row[$column->name] ?? '');
            }, $this->columns);
        }, $data);

        return [
            $this->serialize($this->rows, raw: true),
            $this->serialize($rows),
        ];
    }

    /** @param  string[][]  $rows */
    private function serialize(array $rows, bool $raw = false): string
    {
        $data = [[]];

        foreach ($this->columns as $columnIndex => $column) {
            $data[0][$columnIndex] = $column->header();

            foreach ($rows as $rowIndex => $row) {
                $data[$rowIndex + 1][$columnIndex] = $column->format($row[$columnIndex], $raw);
            }
        }

        return PHP_EOL.implode(PHP_EOL, array_map(function (array $row) {
            return '| '.implode(' | ', $row).' |';
        }, $data)).PHP_EOL;
    }
}
