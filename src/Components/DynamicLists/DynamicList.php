<?php

namespace Nicat\FormFactory\Components\DynamicLists;

use Nicat\FormFactory\Components\Additional\ErrorWrapper;
use Nicat\FormFactory\Utilities\AutoTranslation\AutoTranslator;
use Nicat\FormFactory\Components\FormControls\Button;
use Nicat\FormFactory\FormFactory;
use Nicat\FormFactory\Utilities\FormFactoryTools;
use Nicat\HtmlFactory\Components\AlertComponent;
use Nicat\HtmlFactory\Elements\Abstracts\ContainerElement;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\FieldsetElement;
use Nicat\HtmlFactory\Attributes\Traits\AllowsDisabledAttribute;
use Nicat\HtmlFactory\Attributes\Traits\AllowsNameAttribute;

class DynamicList extends FieldsetElement
{

    /**
     * The base-array-name of all fields within this dynamic list (e.g. "users" or "users[][emails]")
     *
     * @var string
     */
    private $arrayName;

    /**
     * Gets used, if this dynamic list is part of another dynamic list's template.
     *
     * @var string
     */
    private $originalArrayName;

    /**
     * An Element, that can be a DynamicListTemplate (must implement DynamicListTemplateInterface)
     *
     * @var Element|DynamicListTemplateInterface
     */
    private $template;

    /**
     * Minimum items of this dynamic list. (Gets auto-fetched from rules, if possible. Defaults to 1.)
     *
     * @var int|null
     */
    private $minItems;

    /**
     * Maximum items of this dynamic list. (Gets auto-fetched from rules, if possible. Defaults to 10.)
     *
     * @var int|null
     */
    private $maxItems;

    /**
     * Gets set to true, if this dynamic list is part of the template for a parent dynamic list.
     *
     * @var bool|null
     */
    public $isTemplateChild;

    /**
     * Gets set to true, if the template for this dynamic list contains another child dynamic list.
     *
     * @var bool
     */
    public $containsChildDynamicList = false;

    /**
     * The formFactory-instance.
     *
     * @var FormFactory
     */
    private $formFactory;

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
     * @var null|string
     */
    private $addButtonLabel;

    /**
     * DynamicList constructor.
     *
     * @param string $arrayName : The base-array-name of all fields within this dynamic list (e.g. "users" or "users[][emails]")
     * @param DynamicListTemplateInterface $template : An Element, that can be a DynamicListTemplate (must implement DynamicListTemplateInterface)
     * @param string|null $addButtonLabel : The label for the button to add a new item. (Gets auto-translated, if possible.)
     * @param int|null $minItems : Minimum items of this dynamic list. (Gets auto-fetched from rules, if possible. Defaults to 1.)
     * @param int|null $maxItems : Maximum items of this dynamic list. (Gets auto-fetched from rules, if possible. Defaults to 10.)
     */
    public function __construct($arrayName, DynamicListTemplateInterface $template, $addButtonLabel = null, $minItems = null, $maxItems = null)
    {
        parent::__construct();

        $this->arrayName = $arrayName;
        $template->isDynamicListTemplate = true;
        $this->template = $template;
        $this->minItems = $minItems;
        $this->maxItems = $maxItems;
        $this->formFactory = app(FormFactory::class);
        $this->containsChildDynamicList = $this->templateContainsDynamicChild();
        $this->addButtonLabel = $addButtonLabel;
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
     */
    protected function afterDecoration()
    {

        parent::afterDecoration();

        $this->addErrorWrapperForArrayErrors();

        $this->establishMinAndMaxItems();

        $this->template->data('dynamiclist-group', $this->getDynamicListGroupID());

        $this->template->performDynamicListModifications($this,$this->removeItemButton);

        // Add any children, that should already be rendered on output.
        // (e.g. default-values or submitted values).
        $this->addPreRenderedItems();

        // Add an empty template used by JavaScript to add an empty row.
        $this->addJavaScriptTemplate();

        // Add the "Add new row" button.
        $this->appendContent($this->addItemButton);

        // Add the tag for the "maximum reached" alert.
        $this->appendContent($this->maximumReachedAlert);
    }

    /**
     * Hashes the dynamicListArrayName to generate a unique groupID used by JavaScript functionality.
     *
     * @return string
     */
    protected function getDynamicListGroupID()
    {
        $groupID = md5($this->arrayName);
        // If this dynamic-list is a child of another parent dynamic-list-template,
        // we also append a marker, which the JS will replace with the item-ID
        // of a new item created from that parent-template.
        // Otherwise we would end up with identical IDs.
        if ($this->isTemplateChild) {
            $groupID .= '%parentItemID%';
        }
        return $groupID;
    }

    /**
     * Establishes list of itemKeys, that should already be rendered on output.
     * (e.g. default-values or submitted values).
     */
    protected function addPreRenderedItems()
    {
        // Add all required dynamic list children.
        foreach ($this->getItemListToPreRender() as $itemKey) {

            // If this dynamic list is part of a parent dynamic list's template,
            // we must disable all input-fields by default.
            // Otherwise they are submitted by the browser.
            $disableChildren = false;
            if ($this->isTemplateChild) {
                $disableChildren = true;
            }

            $this->addDynamicListItem($itemKey, $disableChildren);
        }

    }

    /**
     * Generates a dynamic list item and adds it to $this->children.
     *
     * @param int|string $itemID
     * @param bool $disableFields
     */
    protected function addDynamicListItem($itemID, $disableFields = false)
    {

        $template = $this->cloneTemplate();

        // If the template of this dynamic list contains additional nested dynamic lists,
        // we must inject the $itemID to the arrayName of these child dynamic lists.
        if ($this->containsChildDynamicList) {
            $this->injectItemIDToChildDynamicLists($template, $itemID);
        }

        // Set the specific id of this dynamic-list-item.
        $template->data('dynamiclist-id', $itemID);

        // Sets the itemKey in all field-names of all fields contained within this item.
        $this->setHtmlArrayKeyInNameRecursively($template, $itemID);

        // If $disableFields is set to true, we disable all fields contained within this child.
        // This is used for the empty and hidden template, that is used by javascript to create new rows.
        if ($disableFields) {
            $this->disableFieldsRecursively($template);
        }

        // Finally append the child to this tag.
        $this->appendContent($template);
    }

    /**
     * Sets the itemKey for a specific (e.g. pre-rendered) item in all field-names of all children of $tag.
     *
     * @param Element $element
     * @param string $key2set
     */
    protected function setHtmlArrayKeyInNameRecursively(Element $element, $key2set = '')
    {
        $arrayName = $this->originalArrayName ?? $this->arrayName;

        if($element->attributes->isAllowed('name')) {
            $fieldName = $element->attributes->name;
            if (strpos($fieldName, $arrayName . '[]') === 0) {
                /** @var AllowsNameAttribute $element */
                $element->name(str_replace($arrayName . '[]', $this->arrayName . '[' . $key2set . ']', $fieldName));
            }
        }

        if (is_a($element,ContainerElement::class)) {
            /** @var ContainerElement $element */
            foreach ($element->content->get() as $childKey => $child) {
                if (is_a($child,Element::class)) {
                    $this->setHtmlArrayKeyInNameRecursively($child, $key2set);
                }
            }
        }

    }

    /**
     * Disables all fields present in $element or it's children.
     *
     * @param Element $element
     */
    protected function disableFieldsRecursively(Element $element) {

        // If $element can be disabled, we to it.
        if ($element->attributes->isAllowed('disabled')) {
            /** @var AllowsDisabledAttribute $element */
            $element->disabled();
        }

        // If $element has children, we must call disableFieldsRecursively() on them also.
        if (is_a($element,ContainerElement::class)) {
            /** @var ContainerElement $element */
            foreach ($element->content->getChildrenByClassName(Element::class) as $childKey => $child) {
                $this->disableFieldsRecursively($child);
            }
        }

    }


    /**
     * Searches $children for any nested dynamic lists and injects and $itemID to it's arrayName.
     *
     * @param ContainerElement|DynamicListTemplateInterface $template
     * @param $itemID
     */
    public function injectItemIDToChildDynamicLists($template, $itemID)
    {
        $arrayName = $this->originalArrayName ?? $this->arrayName;

        foreach ($template->content->getChildrenByClassName(DynamicList::class) as $childDynamicList) {
            /** @var DynamicList $childDynamicList */
            $childDynamicList->injectDynamicListParentItemID($arrayName, $itemID);
        }
    }

    /**
     * If this dynamic list is part of a template for a parent dynamic-list,
     * we must inject the parent's itemID into $this->arrayName.
     *
     * @param $parentArrayName
     * @param $itemID
     */
    public function injectDynamicListParentItemID($parentArrayName, $itemID)
    {

        // If $itemID ends with "itemID%", it means, this dynamic list is part of a parent dynamic list's template.
        // We need this info later to disable all pre-rendered children.
        if (substr($itemID, -7) === "itemID%") {
            $this->isTemplateChild = true;
        }

        // We also save the dynamic-list(s) original array-name within $this->dynamicListOriginalArrayName.
        // This is needed for the replacement of the name-attributes of any fields within this dynamic list.
        $this->originalArrayName = $this->arrayName;

        $this->arrayName = str_replace($parentArrayName . '[]', $parentArrayName . '[' . $itemID . ']', $this->arrayName);
    }

    /**
     * Tries to get $this->dynamicListMinItems and $this->dynamicListMaxItems from the rules,
     * if not set on dynamicList()-call.
     */
    protected function establishMinAndMaxItems()
    {
        // If $minItems or $maxItems was not set via arguments, we try to get them from the FormFactory.
        if (is_null($this->minItems) || is_null($this->maxItems)) {

            // Get the array-rules from the FormFactory-service.
            /** @var FormFactory $formFactory */
            $formFactory = app(FormFactory::class);
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

        // We also add this info to the addItemButton
        $this->addItemButton
            ->data('dynamiclist-min', $this->minItems)
            ->data('dynamiclist-max', $this->maxItems)
        ;
    }

    /**
     * Adds an error-wrapper to display errors for $this->arrayName.
     * These are mainly min- or max-errors.
     */
    private function addErrorWrapperForArrayErrors()
    {
        $errorWrapper = new ErrorWrapper();
        $errorWrapper->addErrorField($this->arrayName);
        $this->prependContent($errorWrapper);
    }

    /**
     * Establishes a list of item-keys, that should be pre-rendered with this dynamic list.
     *
     * @return int[]
     */
    protected function getItemListToPreRender(): array
    {

        // In case this form was submitted during last request,
        // we have to render each submitted child using the same key it was submitted with.
        if ($this->formFactory->getOpenForm()->wasSubmitted && $this->formFactory->getOpenForm()->values->fieldHasSubmittedValue($this->arrayName)) {
            $submittedArray = $this->formFactory->getOpenForm()->values->getSubmittedValueForField($this->arrayName);
            if (!is_array($submittedArray)) {
                $submittedArray = [];
            }
            return array_keys($submittedArray);
        }

        // In case this form was not submitted during last request,
        // we check, if default-values were handed to the form in the Form::open call.
        // If yes, we have to render each item in the default-array to the dynamicList.
        if (!$this->formFactory->getOpenForm()->wasSubmitted && $this->formFactory->getOpenForm()->values->fieldHasDefaultValue($this->arrayName)) {
            $defaultArray = $this->formFactory->getOpenForm()->values->getDefaultValueForField($this->arrayName);
            if (!is_array($defaultArray)) {
                $defaultArray = [];
            }
            return array_keys($defaultArray);
        }

        // If no data was submitted and no default-data was set, we render the minimum count of items for this array.
        $i = 0;
        $items2PreRender = [];
        while ($i < $this->minItems) {
            $items2PreRender[] = $i;
            $i++;
        }
        return $items2PreRender;
    }

    /**
     * Traverses $template recursively to search for nested DynamicList elements.
     * Returns true if at least one was found.
     *
     */
    private function templateContainsDynamicChild()
    {
        $template  = $this->template;
        if (is_a($template,ContainerElement::class)) {
            /** @var ContainerElement $template */
            if (count($template->content->getChildrenByClassName(DynamicList::class))>0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Add an empty template used by JavaScript to add an empty row.
     */
    protected function addJavaScriptTemplate()
    {
        $this->template->hidden();
        $this->template->addStyle('display:none');
        $this->template->data('dynamiclist-template', true);
        $this->addDynamicListItem('%group'.$this->getDynamicListGroupID().'itemID%', true);
    }

    /**
     * Deep clones $this->template.
     *
     * @return DynamicListTemplateInterface|ContainerElement
     */
    protected function cloneTemplate(): DynamicListTemplateInterface
    {
        return unserialize(serialize($this->template));
    }

    /**
     * Generates the Button used for $this->addItemButton.
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

            $addButtonLabel = trans('Nicat-FormFactory::formfactory.dynamic_list_add_button_title', ['attribute' => $addButtonLabelAttribute]);
        }

        $this->addItemButton = (new Button())
            ->title($addButtonLabel)
            ->content($addButtonLabel)
            ->id($this->formFactory->getOpenForm()->attributes->id.'_dynamic_list_'.$this->getDynamicListGroupID().'_add_button')
            ->data('dynamiclist-add', true)
            ->data('dynamiclist-group', $this->getDynamicListGroupID())
        ;
    }

    /**
     * Generates the AlertComponent used for $this->maximumReachedAlert.
     */
    private function generateMaximumReachedAlert()
    {
        $this->maximumReachedAlert = (new AlertComponent('info'))
            ->hidden()
            ->content(trans('Nicat-FormFactory::formfactory.dynamic_list_maximum_reached'))
            ->data('dynamiclist-maxalert', true)
            ->data('dynamiclist-group', $this->getDynamicListGroupID());
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

        $removeButtonLabel = trans('Nicat-FormFactory::formfactory.dynamic_list_remove_button_title', ['attribute' => $removeButtonAttribute]);

        $this->removeItemButton = (new Button())
            ->title($removeButtonLabel)
            ->content($removeButtonLabel)
            ->context('danger')
            ->data('dynamiclist-remove', true)
        ;
    }


}