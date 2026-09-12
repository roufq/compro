<?php

test('feature tests use an isolated in-memory database', function () {
    expect(config('database.default'))->toBe('sqlite')
        ->and(config('database.connections.sqlite.database'))->toBe(':memory:')
        ->and(app()->configurationIsCached())->toBeFalse();
});
