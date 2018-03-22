<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Translation key to use for automatic translations.
    |--------------------------------------------------------------------------
    |
    | By default FormFactory tries to automatically translate
    | labels, placeholders and help-texts using this base translation-key.
    |
     */
    'translations' => 'validation.attributes',

    /*
    |--------------------------------------------------------------------------
    | 'Honeypot anti-bot protection.
    |--------------------------------------------------------------------------
    |
    | Settings regarding anti-bot protection of forms using a honeypot-field.
    |
     */
    'honeypot' => [

        /*
         * Whether honeypot-protection should be enabled at all.
         */
        'enabled' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Time-limit anti-bot protection.
    |--------------------------------------------------------------------------
    |
    | Settings regarding anti-bot protection of forms using a time-limit.
    |
     */
    'time_limit' => [

        /*
         * Whether time-limit-protection should be enabled at all.
         */
        'enabled' => true,

        /*
         * The default-limit (in seconds) to be used.
         * (Can be overridden explicitly per request via the first parameter of the 'timeLimit'-rule of the request-object.)
         */
        'default_limit' => 5,

    ],

    /*
    |--------------------------------------------------------------------------
    | Captcha anti-bot protection.
    |--------------------------------------------------------------------------
    |
    | Settings regarding anti-bot protection of forms using a captcha-field.
    |
     */
    'captcha' => [

        /*
         * Whether captcha-protection should be enabled at all.
         */
        'enabled' => true,

        /*
         * The number of times a form can be submitted, before a captcha is required.
         * (0 means, the captcha is shown always.)
         * (Can be overridden explicitly per request via the first parameter of the 'captcha'-rule of the request-object.)
         */
        'default_limit' => 2,

        /*
         * The time-span (in minutes) for which the captcha-limit is valid.
         * After reaching the limit for captcha-less submissions, it takes this long,
         * before the user can submit the form again without a captcha.
         * (Can be overridden explicitly per request via the second parameter of the 'captcha'-rule of the request-object.)
         */
        'decay_time' => 2,

    ],

    /*
    |--------------------------------------------------------------------------
    | Ajax-validation.
    |--------------------------------------------------------------------------
    |
    | Settings regarding ajax-validation.
    |
     */
    'ajax_validation' => [

        /*
         * Whether ajax-validation should be enabled at all.
         */
        'enabled' => true,

        /*
         * Should an ajax-validation on form-submission be enabled by default for every form?
         * (Can be overridden explicitly per form by setting the 'ajaxValidation' option
         * of the Form::open call to 'onSubmit' or false.)
         */
        'enable_on_form_submit_by_default' => false,

    ]

];
