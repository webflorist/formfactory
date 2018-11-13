<?php

Route::get('captcha-get', '\FormFactoryTests\Browser\Controllers\CaptchaTestController@get');
Route::post('captcha-post', '\FormFactoryTests\Browser\Controllers\CaptchaTestController@post');

Route::get('honeypot-via-rules', '\FormFactoryTests\Browser\Controllers\HoneypotTestController@getHoneypotViaRules');
Route::get('honeypot-via-request-object', '\FormFactoryTests\Browser\Controllers\HoneypotTestController@getHoneypotViaRequestObject');
Route::post('honeypot-post', '\FormFactoryTests\Browser\Controllers\HoneypotTestController@post');

Route::get('timelimit-get', '\FormFactoryTests\Browser\Controllers\TimeLimitTestController@get');
Route::post('timelimit-post', '\FormFactoryTests\Browser\Controllers\TimeLimitTestController@post');

Route::get('vue-redirect', '\FormFactoryTests\Browser\Controllers\VueFormTestController@getRedirect');
Route::post('vue-redirect', '\FormFactoryTests\Browser\Controllers\VueFormTestController@postRedirect');
Route::get('vue-redirect-target', '\FormFactoryTests\Browser\Controllers\VueFormTestController@getRedirectTarget');