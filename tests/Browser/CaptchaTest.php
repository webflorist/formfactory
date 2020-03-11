<?php

namespace FormFactoryTests\Browser;

use FormFactoryTests\DuskTestCase;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;

class CaptchaTest extends DuskTestCase
{

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testCaptchaSuccess()
    {

        $this->browse(function (Browser $browser) {

            $this->exhaustCaptchaLimit($browser);

            $calculation = Str::before(Str::after($browser->driver->getPageSource(), 'calculation: '), '<sup>');

            $result = eval('return ' . $calculation . ';');
            $this->submitForm($browser, $result);
            $browser->assertSee('validated');
            $this->exhaustCaptchaLimit($browser);
        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testCaptchaFailure()
    {
        $this->browse(function (Browser $browser) {
            $this->exhaustCaptchaLimit($browser);
            $this->submitForm($browser, 'wrong');
            $browser->assertSee('The result is incorrect');
        });
    }



    /**
     * Submits Form.
     *
     * @param Browser $browser
     * @param string $captchaValue
     */
    private function submitForm(Browser $browser, $captchaValue = null)
    {
        if (!is_null($captchaValue)) {
            $browser->type('_captcha', $captchaValue);
        }

        $browser->click('#myFormId_submit');
    }


    /**
     * @param Browser $browser
     * @throws \Exception
     */
    private function exhaustCaptchaLimit(Browser $browser)
    {
        cache()->clear();
        for ($i = 1; $i <= config('formfactory.captcha.default_limit'); $i++) {
            $browser->visit('/captcha-get');
            $this->submitForm($browser);
            $browser->assertSee('validated');
        }
        $browser->visit('/captcha-get');
        $browser->assertSee('Please solve the following calculation');
    }


}