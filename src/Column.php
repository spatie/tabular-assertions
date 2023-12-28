<?php

namespace Spatie\TabularAssertions;

class Column
{
    public int $width;

    public function __construct(
        public string $name,
        public bool $numeric = false,
    ) {
        $this->width = strlen($name);
    }

    public function cell(mixed $data): Cell
    {
        $data = $this->format($data);

        $this->width = max($this->width, strlen($data));

        return new Cell($data);
    }

    private function format(mixed $data): string
    {
        if (! is_string($data)) {
            return '';
        }

        return $data;
    }
}
