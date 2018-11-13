<?php

namespace FormFactoryTests\Browser\Tests\RawVue;

use Laravel\Dusk\Browser;

class RedirectTest extends AbstractRawVueTest
{

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testRedirect()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/vue-redirect');
            $browser->click('#myFormId_submit');
            $this->waitForAndAssertSee($browser, 'validated You will be redirected shortly.');
            $browser->waitForLocation('/vue-redirect-target');
            $this->waitForAndAssertSee($browser, 'redirected');
        });
    }

}