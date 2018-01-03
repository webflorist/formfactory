<?php

Route::get('captcha-get', '\HtmlBuilderTests\Browser\Controllers\CaptchaTestController@get');
Route::post('captcha-post', '\HtmlBuilderTests\Browser\Controllers\CaptchaTestController@post');

Route::get('honeypot-via-rules', '\HtmlBuilderTests\Browser\Controllers\HoneypotTestController@getHoneypotViaRules');
Route::get('honeypot-via-request-object', '\HtmlBuilderTests\Browser\Controllers\HoneypotTestController@getHoneypotViaRequestObject');
Route::post('honeypot-post', '\HtmlBuilderTests\Browser\Controllers\HoneypotTestController@post');

Route::get('timelimit-get', '\HtmlBuilderTests\Browser\Controllers\TimeLimitTestController@get');
Route::post('timelimit-post', '\HtmlBuilderTests\Browser\Controllers\TimeLimitTestController@post');

Route::get('ajaxvalidation-get-on-form-submit', '\HtmlBuilderTests\Browser\Controllers\AjaxValidationTestController@getOnFormSubmit');
Route::get('ajaxvalidation-get-on-field-change', '\HtmlBuilderTests\Browser\Controllers\AjaxValidationTestController@getOnFieldChange');
Route::get('ajaxvalidation-get-on-field-key-up', '\HtmlBuilderTests\Browser\Controllers\AjaxValidationTestController@getOnFieldKeyUp');
Route::post('ajaxvalidation-post', '\HtmlBuilderTests\Browser\Controllers\AjaxValidationTestController@post');

Route::get('dynamic-lists-get', '\HtmlBuilderTests\Browser\Controllers\DynamicListsTestController@get');
Route::get('dynamic-lists-get-with-default-values', '\HtmlBuilderTests\Browser\Controllers\DynamicListsTestController@getWithDefaultValues');
Route::post('dynamic-lists-post', '\HtmlBuilderTests\Browser\Controllers\DynamicListsTestController@post');