<?php

namespace FormFactoryTests;

use Gajus\Dindent\Indenter;
use HtmlFactoryTests\Traits\AssertsHtml;
use Laravel\Dusk\Browser;
use Nicat\FormFactory\FormFactoryFacade;
use Nicat\FormFactory\FormFactoryServiceProvider;
use Nicat\HtmlFactory\HtmlFactoryServiceProvider;
use Orchestra\Testbench\Dusk\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase
{
    use AssertsHtml;

    /**
     * Copies FormFactory-related javascript-files to public directory.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    protected function publishJavaScript($app)
    {
        $publicPath = $app->basePath() . '/public/';
        copy(__DIR__ . '/Browser/public/jquery-3.2.1.min.js', $publicPath . 'jquery-3.2.1.min.js');
        copy(__DIR__ . '/../src/public/js/formfactory.js', $publicPath . 'formfactory.js');
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

        // Setup new View path
        $app['config']->set('view.paths', [
            __DIR__ . '/Browser/views'
        ]);

        $app['config']->set('htmlfactory.decorators', ['bootstrap:v3']);

    }

    protected function setDecorators(array $decorators)
    {
        $this->tweakApplication(function($app) use ($decorators){
            $app['config']->set('htmlfactory.decorators', $decorators);
        });
    }

    protected function setUp()
    {
        parent::setUp();
    }


    /**
     * Nicely prints current page source.
     *
     * @param Browser $browser
     * @throws \Gajus\Dindent\Exception\InvalidArgumentException
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
        return [
            HtmlFactoryServiceProvider::class,
            FormFactoryServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Form' => FormFactoryFacade::class,
        ];
    }

    /**
     * Figure out where the test directory is, if we're an included package, or the root one.
     *
     * @param string $path
     *
     * @return string
     */
    protected function resolveBrowserTestsPath($path = __DIR__)
    {
        $root = dirname(__DIR__);

        return $root . '/tests/Browser';
    }



}
