<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\Filament\FilamentTestSupport;

uses(RefreshDatabase::class);

it('mounts every non-shift filament custom page', function () {
    FilamentTestSupport::actingAsAdmin($this);
    $failures = [];

    foreach (FilamentTestSupport::customPages() as [$pageClass, $params]) {
        try {
            $component = FilamentTestSupport::mountPage($pageClass, $params);

            if (method_exists($component->instance(), 'getForms')) {
                $component->assertFormExists();
            }
        } catch (Throwable $throwable) {
            $failures[] = $pageClass . ': ' . $throwable->getMessage();
        }
    }

    expect($failures)->toBeEmpty(implode(PHP_EOL, $failures));
});
