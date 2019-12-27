<?php

namespace Webflorist\FormFactory\Components\FormControls;

use Webflorist\FormFactory\Components\DynamicLists\DynamicList;
use Webflorist\FormFactory\Components\DynamicLists\DynamicListTemplateInterface;
use Webflorist\FormFactory\Components\Helpers\ErrorContainer;
use Webflorist\FormFactory\FormFactory;
use Webflorist\FormFactory\Utilities\AutoTranslator;
use Webflorist\FormFactory\Utilities\FormFactoryTools;
use Webflorist\HtmlFactory\Attributes\Traits\AllowsDisabledAttribute;
use Webflorist\HtmlFactory\Attributes\Traits\AllowsNameAttribute;
use Webflorist\HtmlFactory\Components\AlertComponent;
use Webflorist\HtmlFactory\Elements\Abstracts\ContainerElement;
use Webflorist\HtmlFactory\Elements\Abstracts\Element;
use Webflorist\HtmlFactory\Elements\ButtonElement;
use Webflorist\HtmlFactory\Elements\DivElement;

class DynamicTextList extends HiddenInput
{


    /**
     * The base-array-name of all fields within this dynamic list (e.g. "users" or "users[][emails]")
     *
     * @var string
     */
    public $arrayName;

    /**
     * Minimum items of this dynamic list. (Gets auto-fetched from rules, if possible. Defaults to 1.)
     *
     * @var int|null
     */
    public $minItems;

    /**
     * Maximum items of this dynamic list. (Gets auto-fetched from rules, if possible. Defaults to 10.)
     *
     * @var int|null
     */
    public $maxItems;

    /**
     * The Button used for adding a new row.
     *
     * @var Button
     */
    public $addItemButton;

    /**
     * The Button used for removing a row.
     *
     * @var Button
     */
    public $removeItemButton;

    /**
     * The AlertComponent used to display the "maximum reached" message.
     *
     * @var AlertComponent
     */
    public $maximumReachedAlert;

    /**
     * The formFactory-instance.
     *
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var null|string
     */
    private $addButtonLabel;

    /**
     * DynamicList constructor.
     *
     * @param string $arrayName : The base-array-name of all fields within this dynamic list (e.g. "users" or "users[][emails]")
     * @param string|null $addButtonLabel : The label for the button to add a new item. (Gets auto-translated, if possible.)
     * @param int|null $minItems : Minimum items of this dynamic list. (Gets auto-fetched from rules, if possible. Defaults to 1.)
     * @param int|null $maxItems : Maximum items of this dynamic list. (Gets auto-fetched from rules, if possible. Defaults to 10.)
     */
    public function __construct($arrayName, $addButtonLabel = null, $minItems = null, $maxItems = null)
    {
        parent::__construct($arrayName);

        $this->arrayName = $arrayName;
        $this->minItems = $minItems;
        $this->maxItems = $maxItems;
        $this->addButtonLabel = $addButtonLabel;
        $this->formFactory = FormFactory::singleton();
    }


    /**
     * Gets called before applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function beforeDecoration()
    {
        $this->generateAddItemButton();
        $this->generateRemoveItemButton();
        $this->generateMaximumReachedAlert();
    }

    /**
     * Gets called after applying decorators.
     * Overwrite to perform manipulations.
     *
     * @throws \Webflorist\FormFactory\Exceptions\OpenElementNotFoundException
     */
    protected function afterDecoration()
    {

        parent::afterDecoration();

        $this->establishMinAndMaxItems();
    }



    /**
     * Hashes the dynamicListArrayName to generate a unique groupID used by JavaScript functionality.
     *
     * @return string
     */
    protected function getDynamicListGroupID()
    {
        return md5($this->arrayName);
    }

    /**
     * Tries to get $this->dynamicListMinItems and $this->dynamicListMaxItems from the rules,
     * if not set on dynamicList()-call.
     *
     * @throws \Webflorist\FormFactory\Exceptions\OpenElementNotFoundException
     */
    protected function establishMinAndMaxItems()
    {
        // If $minItems or $maxItems was not set via arguments, we try to get them from the FormFactory.
        if (is_null($this->minItems) || is_null($this->maxItems)) {

            // Get the array-rules from the FormFactory-service.
            /** @var FormFactory $formFactory */
            $formFactory = FormFactory::singleton();
            $arrayRules = $formFactory->getOpenForm()->rules->getRulesForField(
                $this->originalArrayName??$this->arrayName
            );

            // Set minimum count of items from the gathered rules, or use default value.
            if ((is_null($this->minItems)) && isset($arrayRules['min'][0])) {
                $this->minItems = $arrayRules['min'][0];
            } else {
                $this->minItems = 1;
            }

            // Set maximum count of items from the gathered rules, or use default value.
            if ((is_null($this->maxItems)) && isset($arrayRules['max'][0])) {
                $this->maxItems = $arrayRules['max'][0];
            } else {
                $this->maxItems = 10;
            }
        }
    }

    /**
     * Generates the Button used for $this->addItemButton.
     *
     * @throws \Webflorist\FormFactory\Exceptions\OpenElementNotFoundException
     */
    private function generateAddItemButton()
    {
        $addButtonLabel = $this->addButtonLabel;
        // If no add-button-label was stated, we try to auto-translate it.
        if (is_null($addButtonLabel)) {

            // The default add-button-label contains an attribute.
            // We try to auto-translate it.
            $arrayStrippedAttribute = FormFactoryTools::arrayStripString($this->arrayName);
            $addButtonLabelAttribute = AutoTranslator::autoTranslate(
                $arrayStrippedAttribute,
                ucfirst($arrayStrippedAttribute)
            );

            $addButtonLabel = trans('webflorist-formfactory::formfactory.dynamic_list_add_button_title', ['attribute' => $addButtonLabelAttribute]);
        }

        $fieldName = $this->attributes->name;
        $this->addItemButton = (new Button())
            ->title($addButtonLabel)
            ->content($addButtonLabel)
            ->id($this->formFactory->getOpenForm()->getId().'_dynamic_list_'.$this->getDynamicListGroupID().'_add_button')
            ->vOnClick("fields['$fieldName'].value.push('')")
        ;
    }

    /**
     * Generates the AlertComponent used for $this->maximumReachedAlert.
     */
    private function generateMaximumReachedAlert()
    {
        $this->maximumReachedAlert = (new AlertComponent('info'))
            ->content(trans('webflorist-formfactory::formfactory.dynamic_list_maximum_reached'));
    }

    /**
     * Generates the Button used for $this->removeItemButton.
     */
    private function generateRemoveItemButton()
    {
        // The default remove-button-label contains an attribute.
        // We try to auto-translate it.
        $arrayStrippedAttribute = FormFactoryTools::arrayStripString($this->arrayName);
        $removeButtonAttribute = AutoTranslator::autoTranslate(
            $arrayStrippedAttribute,
            ucfirst($arrayStrippedAttribute)
        );

        $removeButtonLabel = trans('webflorist-formfactory::formfactory.dynamic_list_remove_button_title', ['attribute' => $removeButtonAttribute]);

        $this->removeItemButton = (new Button())
            ->title($removeButtonLabel)
            ->content($removeButtonLabel)
            ->context('danger')
            ->id($this->formFactory->getOpenForm()->getId().'_dynamic_list_'.$this->getDynamicListGroupID().'_remove_button')
            ->data('dynamiclist-remove', true)
        ;
    }




}
