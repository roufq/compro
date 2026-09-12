<?php

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

test('expired web sessions return to the form with a safe message', function () {
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
        ->and($response->getTargetUrl())->toBe($formUrl)
        ->and(session('error'))->toBe('Sesi Anda telah berakhir. Silakan periksa kembali formulir lalu kirim ulang.')
        ->and(session()->getOldInput('name'))->toBe('Original IP Baru')
        ->and(session()->getOldInput('password'))->toBeNull()
        ->and(session()->getOldInput('_token'))->toBeNull();
});
