<?php

declare(strict_types=1);

test('Code is strict typed')
    ->expect(['Domain', 'Core', 'App'])
    ->toUseStrictTypes();

test('Not use dump')
    ->expect(['dd', 'dump'])
    ->not->toBeUsed();

test('Not use models in App')
    ->expect('App\Models')
    ->not->toBeClasses();
