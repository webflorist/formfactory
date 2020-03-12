<?php

namespace FormFactoryTests\Browser\Tests\Traits;

use Illuminate\Support\Str;
use Laravel\Dusk\Browser;

trait CaptchaTestTrait
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
            $this->submitCaptchaForm($browser, $result);
            $this->waitForAndAssertSee($browser, 'validated');
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
            $this->submitCaptchaForm($browser, 'wrong');
            $this->waitForAndAssertSee($browser, 'The result is incorrect');
        });
    }



    /**
     * Submits Form.
     *
     * @param Browser $browser
     * @param string $captchaValue
     */
    private function submitCaptchaForm(Browser $browser, $captchaValue = null)
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
            $this->submitCaptchaForm($browser);
            $this->waitForAndAssertSee($browser, 'validated');
        }
        $browser->visit('/captcha-get');
        $this->waitForAndAssertSee($browser, 'Please solve the following calculation');
    }


}