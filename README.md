# nicat/formbuilder
**Convenient and powerful bootstrap (v4) conform form--builder for Laravel 5.5**

## Description
This package provides a form-builder for building whole forms in Laravel 5.1 views without the need to write any HTML.

The main features are:
* Built for easy usage with IDEs (avoiding the use of magic methods to allow auto-completion of IDEs)
* Automatic mapping of Laravel-validation-rules into HTML5-attributes for live-validation by the browser (e.g. laravel rule "required" results in the "required"-property of the HTML-field)
* Automatic mapping and display of Laravel-error-messages next to their corresponding form-fields.
* Extensive auto-translation-functionality (for field-labels, -placeholders and -help-texts)
* Allows multiple forms per page with correct values- and error-mappings.
* Bootstrap (v4)-conform output
* Accessibility-conform output (e.g. using ARIA-attributes and outputting valid HTML 5)
* Easy pre-population of form-fields via a predefined value-array.
* On-board AJAX-validation-functionality (onSubmit of form and/or onKeyup/onChange of field)
* Anti-bot-mechanisms (honeypot-field, captcha, time-limit)
* ...and many more.

## Installation
1. Require the package via composer:  `composer require nicat/htmlbuilder`
2. Add the Service-Provider to config/app.php:  `Nicat\HtmlBuilder\HtmlBuilderServiceProvider::class`
3. Add the Form-facade to config/app.php: `'Form' => Nicat\FormBuilder\FormBuilderFacade::class`
4. Publish config and javascript:  `php artisan vendor:publish --provider="Nicat\HtmlBuilder\HtmlBuilderServiceProvider"`
5. Include the published javascript-file (`public/vendor/nicat/htmlbuilder/js/formbuilder.js`) in your master-template (only required for ajax-validation and dynamic-list-functionality).

## Configuration
The package can be configured via config/htmlbuilder.php. Please see the inline-documentation of this file for explanations of the various settings:
https://github.com/nic-at/htmlbuilder/blob/develop/src/config/htmlbuilder.php

## Usage

### Basics

A HTML-tag for a Form (e.g. an input-field) is generated using the corresponding method of the `Form-`facade. This method may require one or more parameters for setting mandatory information (mostly the "name" attribute in case of fields). Furthermore, you can manipulate the generated output by "chaining" further methods (e.g. to set attributes). The final HTML-string is generated using the `generate()`-method. But since the `Tag`-class of the HtmlBuilder-package includes a magic `__toString()`-method doing exactly that, you can omit the `generate()`-method when using the Form-facade in a blade-template.

#### A minimal example to create a tag
Here is a very basic example for the generation of a text-input from within a laravel-blade-template:
```
Blade Code:
-----------
{!! Form::text('MyFieldName') !!}

Generated HTML:
---------------
<fieldset class="form-group">
    <label for="_MyFieldName">MyFieldName</label>
    <input id="_MyFieldName" class="form-control" name="MyFieldName" value="" placeholder="MyFieldName" type="text">
</fieldset>
```
Note, that the output is Bootstrap (v4) conform and the label as well as placeholder are automatically added using the field-name (if none other is stated), or an automatic translation (explained later).
The field also automatically gets an ID in the following format: `%formID%_%fieldName%`. (In the example above, the `%formID%` is missing, because we have not created a form-open-tag prior to our field.)

Since this package is built IDE-friendly way, you just have to type `Form::`in your auto-completion-enabled IDE and you should immediately get a list of available tags.

#### Using tag-methods to change the output

Now let's say, we want change the output in the following way:
* Set a specific label,
* add a data-attribute `data-foo` with the value `bar`,
* do not use the placeholder,
* set the HTML5 attribute "required",
* and pre-populate the field with a value.

We can achieve this by applying the corresponding methods for these modifications:

```
Blade Code:
-----------
{!! Form::text('MyFieldName')
    ->label('MyNewLabel')
    ->data('foo','bar')
    ->placeholder(false)
    ->required()
    ->value('MyNewValue') !!}

Generated HTML:
---------------
<fieldset class="form-group">
    <label for="_MyFieldName">MyNewLabel<sup>*</sup></label>
    <input id="_MyFieldName" data-foo="bar" class="form-control" required="required" name="MyFieldName" value="MyNewValue" type="text">
</fieldset>
```
Note, that a `<sup>*</sup>` is automatically added to the label. This is the case, because we added the `required`-attribute, and indicates to the user, that this is a mandatory field.

The methods, that can be applied to a tag consist of the HTML-attributes, that this tag is allowed to have, as well as other methods to achieve more specific goals. You can find a full overview of all available methods for a tag below. But as a rule-of-thumb, the methods to set HTML-attributes are always identical to their name (with a few exceptions).

Here are some often used non-HTML-attribute methods:
* ->content('foobar'): sets the content of a tag, that can have one (e.g. div, button, textarea)
* ->data('foo','bar'): adds a data-tag; Note: `data-` is automatically prepended to the key, so this example would result in: `data-foo="bar"`
* ->addClass('foobar'): adds a class to the tag (add multiple classes by either calling addClass multiple times, or by separating the classes with a space.

But since this package is built IDE-friendly way, you just have to type e.g. `Form::text()->`in your auto-completion-enabled IDE and you should immediately get a list of available methods for this tag.

Since this package strives to only output valid HTML, the available methods differ from tag to tag. E.g. you can not use the method ->selected() on an input-tag, because it is not allowed according to HTML-standards.
 
#### A minimal example to create a full form

Of course a minimal form requires a form-open-tag, a field, a submit-button and a form-close-tag.

Let's do this:

```
Blade Code:
-----------
{!! Form::open('MyFormID') !!}
{!! Form::text('MyFieldName') !!}
{!! Form::submit('MySubmitButton') !!}
{!! Form::close() !!}

Generated HTML:
---------------
<form action="http://localhost/formtest" method="POST" role="form" accept-charset="UTF-8" class="form-vertical" enctype="multipart/form-data" id="MyFormID">
    <input id="MyFormID__token" class="form-control" name="_token" value="ALAPXIOaRs3gBV8vYm7vRXgqONRMsQ6cDRUVVaXW" type="hidden">
    <input id="MyFormID__formID" class="form-control" name="_formID" value="MyFormID" type="hidden">
    <fieldset class="form-group">
        <label for="MyFormID_MyFieldName">MyFieldName</label>
        <input id="MyFormID_MyFieldName" class="form-control" name="MyFieldName" value="" placeholder="MyFieldName" type="text">
    </fieldset>
    <button type="submit" class="btn btn-primary" id="MyFormID_MySubmitButton" name="MySubmitButton">MySubmitButton</button>
</form>
```
As you can see, the `Form::open()` call already does some stuff for us like setting some default-attributes (e.g. POST as the method or the current url as the action), adding a hidden input including the laravel CSRF-token and a hidden input including the form-id (which is used for various functionality).

The button already comes with a `type="submit"`, the name is automatically used as the content, and it's classes are bootstrap conform "btn btn-primary". If you want to use a different button-style (e.g. `btn-danger` instead of `btn-primary`, you can do this via the method `->context('danger')`.

As with all other tags, the opening form-tag, as well as the submit-button can be manipulated by applying methods to it (e.g. to change the default-attributes or add additional attributes).

### Advanced Features

Now, that the basic usage of this package was explained, let's continue with some advanced functionality:

#### Pre-populating the whole form with values

In many cases, you have existing data your form should be pre-populated with (e.g. a user editing his data). An array of all values for the whole form can be given to the form-open-call using the ->values() method.

Example:
```
Controller Code:
----------------
return view('MyView')->with([
    'values' => [
        'user' => 'me',
        'newsletter' => 'yes'
    ]
]);

Blade Code:
-----------
{!! Form::open('profile')->values($values) !!}
{!! Form::text('user') !!}
{!! Form::checkbox('newsletter','yes') !!}
{!! Form::submit('submit') !!}
{!! Form::close() !!}

Generated HTML:
---------------
<form action="http://localhost/formtest" method="POST" role="form" accept-charset="UTF-8" class="form-vertical" enctype="multipart/form-data" id="profile">
    <input type="hidden" id="profile__token" class="form-control" name="_token" value="ALAPXIOaRs3gBV8vYm7vRXgqONRMsQ6cDRUVVaXW">
    <input type="hidden" id="profile__formID" class="form-control" name="_formID" value="profile">
    <fieldset class="form-group">
        <label for="profile_user">User</label>
        <input type="text" id="profile_user" class="form-control" name="user" value="me" placeholder="User">
    </fieldset>
    <div class="checkbox">
        <label>
            <input type="checkbox" id="profile_newsletter" name="newsletter" value="yes" checked="checked"> Newsletter
        </label></div>
<button type="submit" class="btn btn-primary" id="profile_submit" name="submit">Submit</button>
</form>
```
As you can see, the values provided from the controller to the view and then to the Form::open() call via the ->values() method pre-populate the fields. Checkboxes, radio-buttons and select-boxes have their checked/selected-attributes applied automatically depending on the provided values.

#### Mapping "old input" and errors to form-fields in case of validation errors

In a normal Laravel-application a user is redirected back to the form-page, if any validation-errors have occurred.

HtmlBuilder automatically maps previously entered "old" input-values as well as error messages back to the submitted form. This even works, when multiple forms with identically named fields are present on the same page. Since the unique form-ID is submitted with the form itself and stored in the session, HtmlBuilder will always know, which form was submitted, and pre-fill any fields (incl. radiobuttons or chackboxes) with the old input accordingly and display any error messages on validation errors.

Per default HtmlBuilder searches the in the `default`-ViewErrorBag of Laravel. If you have stated a specifically named error bag during validation, you will have to tell HtmlBuilder which error-bag it should use to look for errors for your form. You can do this by using the `->errorBag()`-method on the `Form::open()`-call.

If you do not want to let HtmlBuilder fetch errors from the session, but state them yourself, you have 2 possible options:
* You can state the complete multidimensional error-array for all fields of a form using the `->errors()`-method on the `Form::open()` call.
* Or you can state errors for a single field by handing a simple one-dimensional array to the `->errors()`-method of any field (e.g. `Form::text('myField')->errors(['My first error message','My second error message'])`.

The order of precedence with error-fetching is as follows:
errors stated with a specific field > errors stated with the `Form::open()` call > errors fetched from session.

##### Mapping laravel-rules to HTML5-attributes

Several attributes (e.g. `required`, `max`, `min`, `pattern`, etc.) were introduced with HTML5 to add validation-relevant information to fields. With the help of these attributes, modern browsers can validate user input without any server-side requests.

These attributes correspond directly to build-in Laravel-rules, so HtmlBuilder provides several ways generate these attributes from Laravel-rules. Additionally it will also change the input-type of a text-field accordingly. And it will append `<sup>*</sup>` to required fields to mark them as mandatory.

Here is a list of Laravel-rules translated into HTML-attributes or forcing a specific input-type:

Rule | Attribute(s) | Input-Type
-------|--------|--------
accepted|required="required", additionally the field label is appended with `<sup>*</sup>`|-
required|required="required", additionally the field label is appended with `<sup>*</sup>`|-
numeric|pattern="[+-]?\d*\.?\d+"|number
not_numeric|pattern="\D+"|-
url|-|url
active_url|-|url
alpha|pattern="[a-zA-Z]+"|-
alpha_dash|pattern="[a-zA-Z0-9_\-]+"|-
alpha_num|pattern="[a-zA-Z0-9]+"|-
between:min,max|min="min", max="max" (when used on `type=number`- input-tag)|-
between:min,max|maxlength="max", pattern=".{min,max}" (when used on any other input-tag)|-
between:min,max|maxlength="max" (when used on a textarea )|-
in:foo,bar,...|pattern="^(foo\|bar\...)$"|-
not_in:foo,bar,...|pattern="(?:(?!^foo$\|^bar$\|^...$).)*"|-
max:value|max="value" (when used on `type=number`- input-tag)|-
max:value|maxlength="value" (when used on any other tag)|-
min:value|min="value" (when used on `type=number`- input-tag)|-
min:value|pattern=".{min,}" (when used on any other tag)|-
mimes:foo,bar,...|accept=".foo,.bar,...."

There are 3 possible ways of telling HtmlBuilder these rules:
* If you are using a [Laravel Form Request](https://laravel.com/docs/master/validation#form-request-validation) with your form, you can simply state the class-name of that Form Request using the `->requestObject()`-method on the `Form::open()`-call. HtmlBuilder will then fetch the rules from that object automatically. If your Form Request Object resides in the default namespace (`App\Http\Requests`), you can simply state the simple class-name (e.g. `->requestObject('MyFormRequest')`). Otherwise, you have to state the full class-name incl. the namespace.  
* You can state the complete associative rules-array for all fields of a form using the `->rules()`-method on the `Form::open()` call.
* Or you can state the rules for a single field by handing them as a string to the `->errors()`-method of any field.

#### Automatic translation

Another useful feature of HtmlBuilder is it's usage of auto-translation, which tries to automatically translate labels, button- or option-texts, placeholders, and general help-texts via standard laravel-translation-files.
 
Here is a list of tags, that can be auto-translated (see example below for required language-file-keys):
* Labels
* Placeholders
* Help-Texts
* Selectbox-Options
* Radio-Options
* Button-Texts

Please note, that if you specifically state this information with the appropriate methods on the call to generate a specific field, that will always take precedence over auto-translation. E.g. `Form::text('myTextField')->label('Use this label')` will always display 'Use this label' as the label - regardless of any available language-keys.

There are two possible sources you can use for auto-translation:
* If you are using the [nicat/extended-validation](https://github.com/nic-at/extended-validation) package with your application, it will automatically fetch the translations from the attributes registered with the registerAttribute-functionality of that package. This is quite logical, since that functionality is for showing the actual field-names (=attributes) within error messages, so we already have all we need in one place.
* If you are not using [nicat/extended-validation](https://github.com/nic-at/extended-validation) package HtmlBuilder is trying to get translations from a single language-file. You have to state this in the `formbuilder.translations` config key of the htmlbuilder-config (default is `validation.attributes`, which is also Laravel's default location for attributes.

Let's see an example of the second variant:

```
Contents of the 'attributes' sub-array of \resources\lang\en\validation.php
---------------------------------------------------------------------------
'myTextField' => 'My Beautiful Field',
'myTextFieldPlaceholder' => 'I love nicat/htmlbuilder!',
'myTextFieldHelpText' => 'Please enter something nice!',
'mySelectBox' => 'My Beautiful Select-Box',
'mySelectBox_myFirstOption' => 'My First Select-Box-Option',
'mySelectBox_mySecondOption' => 'My Second Select-Box-Option',
'myRadioGroup' => 'My Radio-Group',
'myRadioGroup_myFirstOption' => 'My First Radio-Option',
'myRadioGroup_mySecondOption' => 'My Second Radio-Option',
'mySubmitButton' => 'Submit this beautiful form!',

Blade Code:
-----------
{!! Form::open('myForm') !!}
{!! Form::text('myTextField') !!}
{!! Form::select('mySelectBox',[
        Form::option('myFirstOption'),
        Form::option('mySecondOption')
    ]) !!}
{!! Form::radioGroup('myRadioGroup',[
        Form::radio('myFirstOption'),
        Form::radio('mySecondOption'),
    ]) !!}
{!! Form::submit('mySubmitButton') !!}
{!! Form::close() !!}

Generated HTML:
---------------
<form action="http://lvsandbox.dev.local/formtest" method="POST" role="form" accept-charset="UTF-8" class="form-vertical" enctype="multipart/form-data" id="myForm">
    <input id="myForm__token" class="form-control" name="_token" value="W8CKPo9Fq2X66oI6CokUFz9s6fsjifYShSHQbz0d" type="hidden">
    <input id="myForm__formID" class="form-control" name="_formID" value="myForm" type="hidden">
    <fieldset class="form-group">
        <label for="myForm_myTextField">My Beautiful Field</label>
        <input id="myForm_myTextField" class="form-control" aria-describedby="myForm_myTextField_helpText" name="myTextField" value="" placeholder="I love nicat/htmlbuilder!" type="text">
        <div class="text-muted small" id="myForm_myTextField_helpText">Please enter something nice!</div>
    </fieldset>
    <fieldset class="form-group">
        <label for="myForm_mySelectBox">My Beautiful Select-Box</label>
        <select id="myForm_mySelectBox" class="form-control" name="mySelectBox">
            <option id="myForm_mySelectBox_myFirstOption" value="myFirstOption">My First Select-Box-Option</option>
            <option id="myForm_mySelectBox_mySecondOption" value="mySecondOption">My Second Select-Box-Option</option>
        </select>
    </fieldset>
    <fieldset class="form-group" id="myForm_myRadioGroup">
        <legend>My Radio-Group</legend>
        <div class="radio">
            <label><input id="myForm_myRadioGroup_myFirstOption" name="myRadioGroup" value="myFirstOption" type="radio"> My First Radio-Option</label>
        </div>
        <div class="radio">
            <label><input id="myForm_myRadioGroup_mySecondOption" name="myRadioGroup" value="mySecondOption" type="radio"> My Second Radio-Option</label>
        </div>
    </fieldset>
    <button type="submit" class="btn btn-primary" id="myForm_mySubmitButton" name="mySubmitButton">Submit this beautiful form!</button>
</form>
```

#### Ajax validation

HtmlBuilder comes with on-board functionality for ajax-validation of forms, which means an ajax-request will be sent to the server to validate your form-data and display any errors without a complete page-reload. The following prerequisites must be fulfilled for a form to have ajax validation:
* `formbuilder.js` must be loaded with your application (see Install-instructions above).
* The config-key `formbuilder.ajax_validation.enabled` must be set to `true` in the `htmlbuilder`-config.
* A [Laravel Form Request Object](https://laravel.com/docs/master/validation#form-request-validation) must be handed over to the `Form::open()`-call via the `->requestObject()`-method (see `rules`-section above for details). This request-object will be used for ajax-validation.

If these are fulfilled, you have the following options for ajax-validation:
* **Complete form validation:** This will perform an ajax-validation of the complete form-data, if the submit-button is clicked. If any errors occur, they will be mapped to and displayed at the corresponding form-fields. If no errors occur with the ajax validation, the form will be properly submitted. You can enable ajax validation on form-submission by adding `->ajaxValidation()` to your `Form::open()` call. You can also set the config-key `formbuilder.ajax_validation.enable_on_form_submit_by_default` to `true` in the htmlbuilder-config, to enable this per default for all your forms. You can then also disable it for selected forms by calling `->ajaxValidation(false)` on your `Form::open()` call.
* **Single field validation:** In addition (or as an alternative) to the complete form validation, you can also validate a single field, every time a change occurs to it. You can do that by adding `->ajaxValidation()` to the generation of a field (e.g. `Form::text('myTextField')->ajaxValidation()`. There are two possible behaviours to trigger such an ajax validation of a single field:
  * `onChange`: This will validate the field, when the `onChange`-event of that field is fired (e.g. when leaving text-field or clicking a radio-button). This is the default-behaviour, when adding `->ajaxValidation()` to your field-generation-call.
  * `onKeyup`: This will validate the field, when the `onKeyup`-event of that field is fired (e.g. every time after pressing a key within a text-field). You can enable this behaviour by passing 'onKeyup' to the `ajaxValidation()`-method (e.g. `Form::text('myTextField')->ajaxValidation('onKeyup')`).
  
Ajax validation-requests will be sent to the route `/formbuilder_validation`, which is automatically registered by the HtmlBuilderServiceProvider (if ajax-validation is enabled in the html-builder-config).

#### Anti-bot mechanisms

HtmlBuilder comes with 3 built-in and easy-to-use solutions to protect a form against bots or ddos-attacks. A primary focus of these mechanisms is to maintain the accessibility of your forms, so screen-readers should have no problem with them. (This is also the reason, why the provided captcha-mechanism used a simple text-based mathematical challenge instead of an image-based captcha.) The support for each of these mechanisms must be enabled in the htmlbuilder-config (e.g. the config-key `formbuilder.honeypot.enabled` enables or disables the support for the honeypot-mechanism). Support for all three mechanisms are enabled by default, but must still be enabled individually for each form. This is done by setting the corresponding rules (either in the [Laravel Form Request Object](https://laravel.com/docs/master/validation#form-request-validation) you hand over to the `Form::open()`-call via the `->requestObject()`-method, or in the rules-array you state via the `->rules()`-method.

The following rules (set within the `rules`method of a request-object) would enable all three mechanisms for any form, that uses this request-object by handing it to the `Form::open()`-call via the `->requestObject()`-method.
```php
public function rules()
{
    return [
       '_honeypot' => 'honeypot',
       '_captcha' => 'captcha',
       '_timeLimit' => 'timeLimit',
    ];
}
```

You can achieve the same by handing these rules to your `Form::open()` call via the `rules()`-method (see details above).

Here is a detailed explanation of each anti-bod mechanism:

##### Honeypot-field

This will automatically generate a hidden text-field with a random field-name, that MUST be submitted with an empty value to successfully validate (which it also does per default, if the form is filled out by a human, since he will never see this field, because it is hidden). Since bots normally tend to automatically fill out all fields of a form (and do not check, if they are actually hidden), many of them will fail validation.
 
##### Timelimit

This mechanic will only successfully validate a submitted form, if a certain time has passed between generation and submission of a form. The thought behind this mechanism is, that bots tend to fill out and submit a form automatically and immediately after receiving it, while a human needs some time for filling out.

A default time-limit, that is automatically used for all forms, that have time-limit-protection enabled, can be set via the config-key `formbuilder.time_limit.default_limit` of the htmlbuilder-config (2 seconds per default). But you can set an individual time-limit by passing it as a rule-parameter to the `timeLimit`-rule. E.g. `'_timeLimit' => 'timeLimit:5'` would set the time-limit for any form using this rule to 5 seconds (thus overriding the default-value in the config file).

##### Captcha

HtmlBuilder has a build-in captcha-protection based on simple mathematical calculations. There are 2 settings relevant to this mechanism:
* The number of times a form can be submitted, before a captcha is required. (0 means, the captcha is shown always.) A default-value can be set via the config-key `formbuilder.captcha.default_limit` of the htmlbuilder-config (2 per default). It can also be overridden per form via the first parameter of the `captcha`-rule.
* The time-span (in minutes) for which the captcha-limit is valid. After reaching the limit for captcha-less submissions, it takes this long, before the user can submit the form again without a captcha. Again, a default-value can be set via the config-key `formbuilder.captcha.decay_time` of the htmlbuilder-config (2 per default). It can also be overridden per form via the second parameter of the `captcha`-rule.

E.g. the rule `'_captcha' => 'captcha:10,5'` would display and require a captcha after 10 form-submissions and for 5 minutes (thus overriding the default-values in the config file).

#### Automatically open bootstrap-modal on error

If your form is located inside a bootstrap-modal, you will probably want to open that modal automatically on page-load, if a validation error occurs. This package already includes functionality to achieve this by stating the `id` of the modal via the `modalId()` method on the `Form::open()` call. Here is an example:
Example:
```
<div class="modal" id="myModalId" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                {!! Form::open('myForm')->modalId('myModalId') !!}
                {!! Form::text('myField') !!}
                {!! Form::submit('submit') !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
```

#### Disable automatic mandatory-field-legend

A `Form::close()` call will automatically add an info-text describing mandatory fields (`* Mandatory fields`) to the end of the form.

If you want to disable this behaviour, simply pass a boolean `false` as the first parameter of the `close()` method.

Example:
```
{!! Form::close(false) !!}
```