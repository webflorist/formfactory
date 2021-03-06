<?php

namespace Webflorist\FormFactory\Vue\Responses;

/**
 * Class to create a success-JsonResponse of a VueForm.
 *
 * Class VueFormSuccessResponse
 * @package Webflorist\FormFactory
 */
class VueFormSuccessResponse extends VueFormResponse
{

    /**
     * VueFormSuccessResponse constructor.
     *
     * @param null $message
     * @param int $status
     */
    public function __construct($message = null, int $status = 200)
    {
        parent::__construct(null, $status);
        $this->vueFormResponseData['message'] = $message ?? trans('Webflorist-FormFactory::formfactory.default_success_message');
    }

    /**
     * Resets the form after delivering response.
     *
     * @return $this
     */
    public function resetForm()
    {
        $this->vueFormResponseData['reset_form'] = true;
        return $this;
    }

    /**
     * Sets an URL to redirect to after delivering response.
     * You can also set a delay to give the user
     * time to read the success message.
     *
     * @param string $url
     * @param int $delay
     * @return $this
     */
    public function redirect(string $url, int $delay=2000)
    {
        $this->vueFormResponseData['message'] .= ' ' . trans('Webflorist-FormFactory::formfactory.redirect_message');
        $this->vueFormResponseData['redirect'] = [
            'url' => $url,
            'delay' => $delay
        ];
        return $this;
    }

}