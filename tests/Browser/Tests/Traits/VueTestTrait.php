<?php

namespace FormFactoryTests\Browser\Tests\Traits;

use Laravel\Dusk\Browser;

trait VueTestTrait
{

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testSuccess()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/vue-success');
            $browser->click('#myFormId_submit');
            $this->waitForAndAssertSee($browser, 'success');
        });
    }

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

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testControllerErrors()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/vue-controller-errors');
            $browser->click('#myFormId_submit');
            $this->waitForAndAssertSee($browser, 'field is required');
            $browser->assertDontSee('validated');
        });
    }


}