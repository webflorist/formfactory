<?php

namespace FormBuilderTests\Browser;

use FormBuilderTests\DuskTestCase;
use Laravel\Dusk\Browser;
use Route;

class TimeLimitTest extends DuskTestCase
{

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testTimeLimitFailure()
    {
        $this->browse(function (Browser $browser) {
            $this->submitForm($browser);
            $browser->assertSee('This form can be sent every 2 seconds. Please wait before sending it again.');
        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testTimeLimitSuccess()
    {
        $this->browse(function (Browser $browser) {
            $this->submitForm($browser,2000);
            $browser->assertSee('validated');
        });
    }

    /**
     * Submits Form.
     *
     * @param Browser $browser
     * @param int $pauseBeforeClick
     */
    private function submitForm(Browser $browser, $pauseBeforeClick=0)
    {
        $browser->visit('/timelimit-get');
        $browser->pause($pauseBeforeClick);
        $browser->click('#myFormId_submit');
    }

}