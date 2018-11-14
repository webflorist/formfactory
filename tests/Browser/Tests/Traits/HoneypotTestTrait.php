<?php

namespace FormFactoryTests\Browser\Tests\Traits;

use Laravel\Dusk\Browser;

trait HoneypotTestTrait
{

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testHoneypotViaRulesSuccess()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/honeypot-via-rules');
            $this->submitForm($browser,'');
            $this->waitForAndAssertSee($browser, 'validated')
            ;
        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testHoneypotViaRulesFailure()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/honeypot-via-rules');
            $this->submitForm($browser,'some text');
            $browser->assertDontSee('validated')
            ;
        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testHoneypotViaRequestObjectSuccess()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/honeypot-via-request-object');
            $this->submitForm($browser,'');
            $this->waitForAndAssertSee($browser, 'validated')
            ;
        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testHoneypotViaRequestObjectFailure()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/honeypot-via-request-object');
            $this->submitForm($browser,'some text');
            $browser->assertDontSee('validated')
            ;
        });
    }

    /**
     * Submits Form.
     *
     * @param Browser $browser
     * @param string $honeypotValue
     */
    private function submitForm(Browser $browser, $honeypotValue = '')
    {

        // Make wrapper of honeypot-field visible to be able to interact with it.
        $browser->script('document.querySelector("#myFormId div").style.display = "block";');
        $browser->script('document.querySelector("#myFormId div").removeAttribute("hidden");');

        // Get name of honeypot-field
        $fieldName = $browser->element("#myFormId div input")->getAttribute('name');

        $browser
            ->type($fieldName,$honeypotValue)
            ->click('#myFormId_submit');
    }
}