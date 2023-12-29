<?php

use Spatie\TabularAssertions\Align;
use Spatie\TabularAssertions\Column;

it('can be created')
    ->expect(Column::create('test'))
    ->name->toBe('test')
    ->width->toBe(4)
    ->dynamic->toBeFalse()
    ->align->toBe(Align::Left);

it('can be created with a different alignment')
    ->expect(Column::create('test', Align::Right))
    ->align->toBe(Align::Right);

it('can be created and marked dynamic')
    ->expect(Column::create('#test'))
    ->name->toBe('test')
    ->width->toBe(5)
    ->dynamic->toBeTrue();
