<?php

namespace FormFactoryTests\Browser;

use Facebook\WebDriver\WebDriverKeys;
use FormFactoryTests\DuskTestCase;
use Laravel\Dusk\Browser;

class AjaxValidationTest extends DuskTestCase
{
    private $getFormTimestamp;

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testAjaxValidationOnFormSubmitSuccess()
    {
        $this->browse(function (Browser $browser) {
            $this->getForm($browser, '/ajaxvalidation-get-on-form-submit');
            $browser->type('text', 'foobar');
            $this->submitForm($browser, false);
            $browser->assertSee('validated');
        });
    }

    /**
     * @param Browser $browser
     * @param $url
     */
    private function getForm(Browser $browser, $url)
    {
        $browser->visit($url);
        $this->getFormTimestamp = $this->getTimestamp($browser);
    }

    /**
     * Submits Form.
     *
     * @param Browser $browser
     * @param bool $expectStayOnPage
     */
    private function submitForm(Browser $browser, $expectStayOnPage = true)
    {
        $browser->click('#myFormId_submit');

        if ($expectStayOnPage) {
            $this->assertEquals($this->getFormTimestamp, $this->getTimestamp($browser));
        } else {
            $this->assertNotEquals($this->getFormTimestamp, $this->getTimestamp($browser));
        }
    }

    private function getTimestamp(Browser $browser)
    {
        return str_before(str_after($browser->driver->getPageSource(), '<timestamp>'), '</timestamp>');
    }

}