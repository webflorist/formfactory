<?php

namespace FormFactoryTests\Browser\Tests\Traits;

use Laravel\Dusk\Browser;

trait TimeLimitTestTrait
{

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testTimeLimitFailure()
    {
        $this->browse(function (Browser $browser) {
            $this->submitForm($browser);
            $this->waitForAndAssertSee($browser, 'This form can be sent every 5 seconds. Please wait before sending it again.');
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
            $this->waitForAndAssertSee($browser, 'validated');
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