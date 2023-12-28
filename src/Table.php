<?php

namespace Spatie\TabularAssertions;

class Table
{
    /**
     * @param  Column[]  $columns
     * @param  Cell[][]  $rows
     */
    public function __construct(
        public array $columns,
        public array $rows,
    ) {
    }
}
