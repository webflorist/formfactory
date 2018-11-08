<?php

namespace Nicat\FormFactory\Components\Helpers;

use Nicat\FormFactory\Components\FormControls\Contracts\FieldInterface;
use Nicat\FormFactory\Components\FormControls\Contracts\FormControlInterface;
use Nicat\FormFactory\Components\FormControls\TextInput;
use Nicat\FormFactory\Exceptions\OpenElementNotFoundException;
use Nicat\FormFactory\FormFactory;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\SupElement;

class RequiredFieldIndicator extends SupElement
{
    /**
     * The field this FieldLabel belongs to.
     *
     * @var Element|FieldInterface|FormControlInterface
     */
    public $field;

    /**
     * RequiredFieldIndicator constructor.
     *
     * @param FieldInterface|string|null $field
     */
    public function __construct($field=null)
    {
        parent::__construct();

        // If we just get a field-name, we create a temporary text-input from it,
        // since a FieldInterface is required for further processing.
        if (is_string($field)) {
            $field = (new TextInput($field));
        }

        $this->field = $field;

        $this->content('*');

        if (!is_null($this->field) && $this->field->isVueEnabled()) {
            $this->vIf( "fields['".$this->field->getFieldName()."'].isRequired");
        }
    }

    /**
     * Manipulate the generated HTML.
     *
     * @param string $output
     */
    protected function manipulateOutput(string &$output)
    {

        if (!is_null($this->field)) {

            // Do not generate output, if field is not required, and vue is not used.
            if (!$this->field->attributes->required && !$this->field->isVueEnabled()) {
                $output = '';
            }

            // Wrap output in template-tags, if vue is enabled,
            // to keep Browser from initial rendering of element.
            if ($this->field->isVueEnabled()) {
                $output = "<template>$output</template>";
            }

        }

        if (strlen($output)>0) {
            try {
                FormFactory::singleton()->getOpenForm()->setRequiredFieldIndicatorUsed();
            } catch (OpenElementNotFoundException $e) {
            }
        }
    }

}