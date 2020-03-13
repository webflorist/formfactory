<?php

namespace FormFactoryTests\Browser;

use Facebook\WebDriver\WebDriverKeys;
use FormFactoryTests\DuskTestCase;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;

class AjaxValidationTest extends DuskTestCase
{
    private $getFormTimestamp;

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testAjaxValidationOnFormSubmitError()
    {
        $this->browse(function (Browser $browser) {
            $this->getForm($browser, '/ajaxvalidation-get-on-form-submit');
            $this->submitForm($browser, true);
            $browser->assertSee('The text field is required.');
        });
    }

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
     * @throws \Exception
     * @throws \Throwable
     */
    public function testAjaxValidationOnFieldChangeError()
    {
        $this->browse(function (Browser $browser) {
            $this->getForm($browser, '/ajaxvalidation-get-on-field-change');
            $browser->type('text', 'a');
            $browser->keys('#myFormId_text',WebDriverKeys::TAB);
            $browser->assertSee('The text must be at least 2 characters.');
        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testAjaxValidationOnFieldKeyUpError()
    {
        $this->browse(function (Browser $browser) {
            $this->getForm($browser, '/ajaxvalidation-get-on-field-key-up');
            $browser->type('text', 'a');
            $browser->assertSee('The text must be at least 2 characters.');
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
        return Str::before(Str::after($browser->driver->getPageSource(), '<timestamp>'), '</timestamp>');
    }

}