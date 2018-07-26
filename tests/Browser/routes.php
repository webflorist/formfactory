<?php

Route::get('captcha-get', '\FormFactoryTests\Browser\Controllers\CaptchaTestController@get');
Route::post('captcha-post', '\FormFactoryTests\Browser\Controllers\CaptchaTestController@post');

Route::get('honeypot-via-rules', '\FormFactoryTests\Browser\Controllers\HoneypotTestController@getHoneypotViaRules');
Route::get('honeypot-via-request-object', '\FormFactoryTests\Browser\Controllers\HoneypotTestController@getHoneypotViaRequestObject');
Route::post('honeypot-post', '\FormFactoryTests\Browser\Controllers\HoneypotTestController@post');

Route::get('timelimit-get', '\FormFactoryTests\Browser\Controllers\TimeLimitTestController@get');
Route::post('timelimit-post', '\FormFactoryTests\Browser\Controllers\TimeLimitTestController@post');

Route::get('ajaxvalidation-get-on-form-submit', '\FormFactoryTests\Browser\Controllers\AjaxValidationTestController@getOnFormSubmit');
Route::get('ajaxvalidation-get-on-field-change', '\FormFactoryTests\Browser\Controllers\AjaxValidationTestController@getOnFieldChange');
Route::get('ajaxvalidation-get-on-field-key-up', '\FormFactoryTests\Browser\Controllers\AjaxValidationTestController@getOnFieldKeyUp');
Route::post('ajaxvalidation-post', '\FormFactoryTests\Browser\Controllers\AjaxValidationTestController@post');

Route::get('dynamic-lists-get', '\FormFactoryTests\Browser\Controllers\DynamicListsTestController@get');
Route::get('dynamic-lists-get-with-default-values', '\FormFactoryTests\Browser\Controllers\DynamicListsTestController@getWithDefaultValues');
Route::post('dynamic-lists-post', '\FormFactoryTests\Browser\Controllers\DynamicListsTestController@post');