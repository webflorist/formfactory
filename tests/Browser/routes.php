<?php

Route::get('captcha-get', '\FormBuilderTests\Browser\Controllers\CaptchaTestController@get');
Route::post('captcha-post', '\FormBuilderTests\Browser\Controllers\CaptchaTestController@post');

Route::get('honeypot-via-rules', '\FormBuilderTests\Browser\Controllers\HoneypotTestController@getHoneypotViaRules');
Route::get('honeypot-via-request-object', '\FormBuilderTests\Browser\Controllers\HoneypotTestController@getHoneypotViaRequestObject');
Route::post('honeypot-post', '\FormBuilderTests\Browser\Controllers\HoneypotTestController@post');

Route::get('timelimit-get', '\FormBuilderTests\Browser\Controllers\TimeLimitTestController@get');
Route::post('timelimit-post', '\FormBuilderTests\Browser\Controllers\TimeLimitTestController@post');

Route::get('ajaxvalidation-get-on-form-submit', '\FormBuilderTests\Browser\Controllers\AjaxValidationTestController@getOnFormSubmit');
Route::get('ajaxvalidation-get-on-field-change', '\FormBuilderTests\Browser\Controllers\AjaxValidationTestController@getOnFieldChange');
Route::get('ajaxvalidation-get-on-field-key-up', '\FormBuilderTests\Browser\Controllers\AjaxValidationTestController@getOnFieldKeyUp');
Route::post('ajaxvalidation-post', '\FormBuilderTests\Browser\Controllers\AjaxValidationTestController@post');

Route::get('dynamic-lists-get', '\FormBuilderTests\Browser\Controllers\DynamicListsTestController@get');
Route::get('dynamic-lists-get-with-default-values', '\FormBuilderTests\Browser\Controllers\DynamicListsTestController@getWithDefaultValues');
Route::post('dynamic-lists-post', '\FormBuilderTests\Browser\Controllers\DynamicListsTestController@post');