<?php

namespace FormFactoryTests\Browser;

use FormFactoryTests\DuskTestCase;
use Laravel\Dusk\Browser;

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
            $browser->assertSee('This form can be sent every 5 seconds. Please wait before sending it again.');
        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testTimeLimitSuccess()
    {
        $this->browse(function (Browser $browser) {
            $this->submitForm($browser,5000);
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
        if ($pauseBeforeClick > 0) {
            $browser->pause($pauseBeforeClick);
        }
        $browser->click('#myFormId_submit');
    }

}