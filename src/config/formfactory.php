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
    | Vue.js Support
    |--------------------------------------------------------------------------
    |
    | Settings regarding support for vue.js.
    | This requires vue.js (2.x) to be available in the frontend.
    |
     */
    'vue' => [

        /*
         * Whether vue-functionality should be enabled at all.
         * If this is false, Form::vOpen() will call Form::open() instead.
         */
        'enabled' => true,

        /*
         * Here you can customize various frontend methods.
         */
        'methods' => [

            // This method displays the success message.
            'display_success_message' => 'function(message) {
                this.successMessage = message;
            }',

        ]

    ],

    /*
    |--------------------------------------------------------------------------
    | Honeypot anti-bot protection.
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
         * The time-span (in seconds since Laravel 5.8; in minutes before Laravel 5.8) for which the captcha-limit is valid.
         * After reaching the limit for captcha-less submissions, it takes this long,
         * before the user can submit the form again without a captcha.
         * (Can be overridden explicitly per request via the second parameter of the 'captcha'-rule of the request-object.)
         */
        'decay_time' => 120,

    ]

];
