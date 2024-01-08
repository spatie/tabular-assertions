<?php

namespace Spatie\TabularAssertions;

enum Align
{
    case Left;
    case Right;

    public function padType(): int
    {
        return match ($this) {
            self::Left => STR_PAD_RIGHT,
            self::Right => STR_PAD_LEFT,
        };
    }
}
