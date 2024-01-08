<?php

namespace Spatie\TabularAssertions;

class Column
{
    /** @var string[] */
    private array $dynamicValues = [];

    private function __construct(
        public string $name,
        public int $width,
        public bool $dynamic,
        public Align $align,
    ) {
    }

    public static function create(string $name, Align $align = Align::Left): self
    {
        $name = trim($name);
        $width = strlen($name);
        $dynamic = str_starts_with($name, '#');

        if ($dynamic) {
            $name = substr($name, 1);
        }

        return new self(
            name: $name,
            width: $width,
            dynamic: $dynamic,
            align: $align,
        );
    }

    public function cell(mixed $data): string
    {
        /** @phpstan-ignore-next-line */
        $data = (string) $data;

        $data = str_replace('|', '-', trim($data));

        $this->width = max($this->width, strlen($data));

        return $data;
    }

    public function header(): string
    {
        return $this->format(($this->dynamic ? '#' : '').$this->name, raw: true);
    }

    public function format(string $data, bool $raw = false): string
    {
        return str_pad(
            string: $raw ? $data : $this->value($data),
            length: $this->width,
            pad_type: $this->align->padType(),
        );
    }

    private function value(string $data): string
    {
        if (! $this->dynamic) {
            return $data;
        }

        /** @var int|false $index */
        $index = array_search($data, $this->dynamicValues, true);

        if ($index === false) {
            $this->dynamicValues[] = $data;

            return '#'.count($this->dynamicValues);
        }

        return '#'.($index + 1);
    }
}
