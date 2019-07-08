<?php

namespace FormFactoryTests\Browser\Tests;

use FormFactoryTests\DuskTestCase;
use Laravel\Dusk\Browser;

class CsrfTokenTest extends DuskTestCase
{
    protected $vueEnabled = true;
    protected $openVueForm = true;
    protected $generateCsrfToken = false;
    protected $csrfTokenAutoFetch = true;

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testCsrfTokenAutoFetchOnError()
    {
        $this->app['env'] = 'production';
        $this->browse(function (Browser $browser) {
            $browser->visit('/vue-success');
            $browser->script("axios.defaults.headers.common['X-CSRF-TOKEN'] = 'invalid'");
            $browser->click('#myFormId_submit');
            $this->waitForAndAssertSee($browser, 'success');
        });
    }
}