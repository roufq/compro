<?php

use App\Models\User;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

test('expired web sessions log the user out and redirect to login', function () {
    $this->actingAs(User::factory()->create());

    $formUrl = route('admin.original-ip.index');
    $request = Request::create($formUrl, 'POST', [
        'name' => 'Original IP Baru',
        'password' => 'rahasia',
        '_token' => 'kedaluwarsa',
    ]);
    $request->headers->set('referer', $formUrl);
    $request->setLaravelSession(app('session.store'));
    app()->instance('request', $request);

    $response = app(ExceptionHandler::class)->render($request, new TokenMismatchException);

    expect($response)->toBeInstanceOf(RedirectResponse::class)
        ->and($response->getTargetUrl())->toBe(route('login'))
        ->and(session('status'))->toBe('Sesi Anda telah berakhir. Silakan masuk kembali.')
        ->and(session()->getOldInput('name'))->toBeNull()
        ->and(session()->getOldInput('password'))->toBeNull()
        ->and(session()->getOldInput('_token'))->toBeNull();

    $this->assertGuest();
});

test('authenticated pages include an automatic session timeout', function () {
    config()->set('session.lifetime', 15);

    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.identitas'))
        ->assertSuccessful()
        ->assertSee('data-auth-session-timeout', escape: false)
        ->assertSee('data-lifetime-milliseconds="900000"', escape: false)
        ->assertSee('data-login-url="'.route('login').'"', escape: false)
        ->assertSee('data-logout-url="'.route('logout').'"', escape: false);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertSee('data-auth-session-timeout', escape: false);
});
