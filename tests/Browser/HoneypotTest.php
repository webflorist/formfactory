<?php

namespace HtmlBuilderTests\Browser;

use HtmlBuilderTests\DuskTestCase;
use Laravel\Dusk\Browser;
use Route;

class HoneypotTest extends DuskTestCase
{

    /**
     * Submits Form.
     *
     * @param Browser $browser
     * @param string $honeypotValue
     */
    private function submitForm(Browser $browser, $honeypotValue = '')
    {

        // Make wrapper of honeypot-field visible to be able to interact with it.
        $browser->script('document.querySelector("#myFormId fieldset").style.display = "block";');

        // Get name of honeypot-field
        $fieldName = $browser->element("#myFormId fieldset input")->getAttribute('name');

        $browser
            ->type($fieldName,$honeypotValue)
            ->click('#myFormId_submit');
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testHoneypotViaRulesSuccess()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/honeypot-via-rules');
            $this->submitForm($browser,'');
            $browser->assertSee('validated')
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
            $browser->assertSee('validated')
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
}