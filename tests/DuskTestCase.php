<?php

namespace FormBuilderTests;

use Gajus\Dindent\Indenter;
use HtmlBuilderTests\Traits\AssertsHtml;
use Laravel\Dusk\Browser;
use Nicat\HtmlBuilder\FormBuilder\FormBuilderFacade;
use Nicat\HtmlBuilder\HtmlBuilderServiceProvider;
use Orchestra\Testbench\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\ProcessUtils;

abstract class DuskTestCase extends BaseTestCase
{
    use AssertsHtml;


    protected $config = [

        'formBuilder' => [

            // Where the FormBuilder should look for translations
            'translations' => 'validation.attributes', // Where the FormBuilder should look for translations

            // Settings regarding anti-bot protection of forms using a honeypot-field.
            'honeypot' => [

                // Whether honeypot-protection should be enabled at all.
                'enabled' => true,

            ],

            // Settings regarding anti-bot protection of forms using a time-limit.
            'timeLimit' => [

                // Whether timeLimit-protection should be enabled at all.
                'enabled' => true,

                // The default-limit (in seconds) to be used.
                // (Can be overridden explicitly per request via the first parameter of the 'timeLimit'-rule of the request-object.)
                'defaultLimit' => 2,

            ],

            // Settings regarding anti-bot protection of forms using a captcha-field.
            'captcha' => [

                // Whether captcha-protection should be enabled at all.
                'enabled' => true,

                // The number of times a form can be submitted, before a captcha is required.
                // (0 means, the captcha is shown always.)
                // (Can be overridden explicitly per request via the first parameter of the 'captcha'-rule of the request-object.)
                'defaultLimit' => 2,

                // The time-span (in minutes) for which the captcha-limit is valid.
                // After reaching the limit for captcha-less submissions, it takes this long,
                // before the user can submit the form again without a captcha.
                // (Can be overridden explicitly per request via the second parameter of the 'captcha'-rule of the request-object.)
                'decayTime' => 2,

            ],

            // Settings regarding ajax-validation.
            'ajaxValidation' => [

                // Whether ajax-validation should be enabled at all.
                'enabled' => true,

                // Should an ajax-validation on form-submission be enabled by default for every form?
                // (Can be overridden explicitly per form by setting the 'ajaxValidation' option
                //  of the Form::open call to 'onSubmit' or false.
                'enable_on_form_submit_by_default' => false,

            ]

        ],
    ];


    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless'
        ]);

        $driverUrl = 'http://' . env('BROWSERTEST_DRIVER_URL', 'localhost') . ':' . env('BROWSERTEST_DRIVER_PORT', 9515);

        return RemoteWebDriver::create(
            $driverUrl, DesiredCapabilities::chrome()->setCapability(
            ChromeOptions::CAPABILITY, $options
        )
        );
    }

    /**
     * Executes HtmlBuilder-related javascript-files.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    protected function publishJavaScript($app)
    {
        $publicPath = $app->basePath() . '/public/';
        copy(__DIR__ . '/Browser/public/jquery-3.2.1.min.js', $publicPath . 'jquery-3.2.1.min.js');
        copy(__DIR__ . '/../src/public/js/htmlbuilder.js', $publicPath . 'htmlbuilder.js');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $this->setupConfiguration($app);
        $this->loadRoutes($app);
        $this->publishJavaScript($app);
    }

    /**
     * Load configuration settings
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function setupConfiguration($app)
    {
        //Cookie Settings
        $app['config']->set('session.expire_on_close', false);

        //Htmlbuilder config.
        $app['config']->set('htmlbuilder', $this->config);

        // Setup new View path
        $app['config']->set('view.paths',[
            __DIR__ . '/Browser/views'
        ]);

    }

    protected function setUp()
    {
        parent::setUp();
    }


    /**
     * Nicely prints current page source.
     *
     * @param Browser $browser
     * @throws \Gajus\Dindent\Exception\RuntimeException
     */
    protected function prettyPrintPageSource(Browser $browser)
    {
        dd((new Indenter())->indent($browser->driver->getPageSource()));
    }


    /**
     * Load Routes
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function loadRoutes($app)
    {
        //set routes for the testsystem
        $app['router']->middleware('web')
            ->group($this->resolveBrowserTestsPath() . '/routes.php');

    }

    protected function getPackageProviders($app)
    {
        return [HtmlBuilderServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Form' => FormBuilderFacade::class,
        ];
    }

    /**
     * Figure out where the test directory is, if we're an included package, or the root one.
     */
    protected function resolveBrowserTestsPath()
    {
        $root = dirname(__DIR__);

        return $root . '/tests/Browser';
    }


    public static function setUpBeforeClass()
    {
        //static::serve(env('BROWSERTEST_SERVER_IP', 'localhost'), env('BROWSERTEST_SERVER_PORT', 8000));
    }

    public static function tearDownAfterClass()
    {
        static::stopServing();
    }


}
