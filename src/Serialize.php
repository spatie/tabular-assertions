<?php

namespace Spatie\TabularAssertions;

class Serialize
{
    /**
     * @param  mixed[][]  $data
     * @param  Column[]  $columns
     */
    public function __invoke(array $columns, array $data): Table
    {
        $rows = array_map(function (array $row) use ($columns) {
            return array_map(function (Column $column) use ($row) {
                return $column->cell($row[$column->name]);
            }, $columns);
        }, $data);

        return new Table($columns, $rows);
    }
}
