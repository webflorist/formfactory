<?php

/**
 * Created by PhpStorm.
 * User: GeraldB
 * Date: 04.11.2015
 * Time: 18:43
 */

namespace FormFactoryTests\Legacy\Traits;

use Illuminate\Support\Str;

trait AppliesTagMethodsToMatcherData
{

    protected function tagMethod2Matcher_defaultAttribute($attribute = '', $value = '')
    {
        $this->matchTagAttributes[$attribute] = $value;
    }

    protected function tagMethod2Matcher_id($id = '')
    {
        $this->matchTagAttributes['id'] = $id;
        if (isset($this->labelMatcher) && (count($this->labelMatcher) > 0)) {
            $this->labelMatcher['attributes']['for'] = $id;
        }
    }

    protected function tagMethod2Matcher_name($name = '')
    {
        $this->matchTagAttributes['name'] = $name;
        if (isset($this->matchTagAttributes['aria-describedby'])) {
            $this->matchTagAttributes['aria-describedby'] = $name . '_errors';
        }
    }

    protected function tagMethod2Matcher_addClass($class = '')
    {
        $this->addHtmlClass2String($this->matchTagAttributes['class'], $class);
    }

    protected function addHtmlClass2String(&$classString = '', $class2Add = '')
    {

        $classesToAdd = explode(' ', trim($class2Add));

        // We only add classes, that don't already exist.
        $existingClasses = explode(' ', $classString);
        foreach ($classesToAdd as $key => $className) {
            if (array_search($className, $existingClasses) === false) {
                $classString .= ' ' . $className;
            }
        }
        $classString = trim($classString);

    }

    protected function tagMethod2Matcher_data($dataAttribute = '', $dataValue)
    {
        $this->matchTagAttributes['data-' . $dataAttribute] = $dataValue;
    }

    protected function tagMethod2Matcher_label($label = '')
    {
        if (isset($this->labelMatcher) && (count($this->labelMatcher) > 0)) {
            $this->labelMatcher['children'] = [
                [
                    'text' => $label
                ]
            ];
            if (isset($this->matchTagAttributes['placeholder'])) {
                $this->matchTagAttributes['placeholder'] = $label;
            }
        }
    }

    protected function tagMethod2Matcher_content($content = '')
    {
        $this->matchTagChildren = [
            [
                'text' => $content
            ]
        ];
    }

    protected function tagMethod2Matcher_context($context = '')
    {
        $this->context = $context;
    }

    protected function tagMethod2Matcher_disabled($disabled = true)
    {
        $this->handleProperty('disabled', $disabled);
    }

    protected function tagMethod2Matcher_checked($checked = true)
    {
        $this->handleProperty('checked', $checked);
    }

    protected function tagMethod2Matcher_autofocus($autofocus = true)
    {
        $this->handleProperty('autofocus', $autofocus);
    }

    protected function tagMethod2Matcher_hidden($hidden = true)
    {
        $this->handleProperty('hidden', $hidden);
    }

    protected function tagMethod2Matcher_readonly($readonly = true)
    {
        $this->handleProperty('readonly', $readonly);
    }

    protected function tagMethod2Matcher_required($required = true)
    {
        $this->handleProperty('required', $required);
        if (isset($this->labelMatcher) && (count($this->labelMatcher) > 0)) {
            $this->labelMatcher['children'][] = [
                'tag' => 'sup',
                'children' => [
                    [
                        'text' => '*'
                    ]
                ]
            ];
        }
    }

    protected function tagMethod2Matcher_labelMode($labelMode = 'bound')
    {
        if ($labelMode === 'sr-only') {
            if (isset($this->labelMatcher) && (count($this->labelMatcher) > 0)) {
                $this->labelMatcher['attributes']['class'] = 'sr-only';
            }
        } else if ($labelMode === 'none') {
            unset($this->labelMatcher);
        }
    }

    protected function tagMethod2Matcher_helpText($helpText = '')
    {
        $this->helpTextMatcher = [
            'tag' => 'small',
            'attributes' => [
                'class' => 'text-muted small',
                'id' => $this->matchTagAttributes['id'] . '_helpText',
            ],
            'children' => [
                [
                    'text' => $helpText
                ]
            ]
        ];

        if (!isset($this->matchTagAttributes['aria-describedby'])) {
            $this->matchTagAttributes['aria-describedby'] = '';
        }
        $this->matchTagAttributes['aria-describedby'] .= $this->matchTagAttributes['id'] . '_helpText';
    }

    protected function tagMethod2Matcher_errors($errors = [])
    {

        if (isset($this->wrapperMatcher) && (count($this->wrapperMatcher) > 0)) {
            $this->addHtmlClass2String($this->wrapperMatcher['attributes']['class'], 'has-error');
        }

        // Hidden error-container
        $this->errorMatcher = [
            'tag' => 'div',
            'attributes' => [
                'role' => 'alert',
                'class' => 'alert alert-danger',
            ],
            'children' => []
        ];

        if (!isset($this->errorMatcher['attributes']['id'])) {
            $this->errorMatcher['attributes']['id'] = $this->formTemplate['parameters']['id'] . '_' . $this->matchTagAttributes['name'].'_errors';
        }

        foreach ($errors as $key => $errorMsg) {
            $this->errorMatcher['children'][] = [
                'tag' => 'div',
                'children' => [
                    [
                        'text' => $errorMsg
                    ]
                ]
            ];
        }

        $this->matchTagAttributes['aria-invalid'] = 'true';

        if (!isset($this->matchTagAttributes['aria-describedby'])) {
            $this->matchTagAttributes['aria-describedby'] = '';
        }
        $this->matchTagAttributes['aria-describedby'] .= $this->formTemplate['parameters']['id'] . '_' . $this->matchTagAttributes['name'] . '_errors';

    }

    protected function tagMethod2Matcher_autocomplete($autocomplete = true)
    {
        if ($autocomplete) {
            $this->matchTagAttributes['autocomplete'] = 'on';
        } else {
            $this->matchTagAttributes['autocomplete'] = 'off';
        }
    }

    protected function tagMethod2Matcher_autoSubmit($autoSubmit = true)
    {
        if ($autoSubmit) {
            $this->matchTagAttributes['data-autosubmit'] = 'onChange';
        } else if (isset($this->matchTagAttributes['data-autosubmit'])) {
            unset($this->matchTagAttributes['data-autosubmit']);
        }
    }

    protected function handleProperty($name = '', $value = true)
    {
        if ($value) {
            $this->matchTagAttributes[$name] = true;
        } else if (isset($this->matchTagAttributes[$name])) {
            unset($this->matchTagAttributes[$name]);
        }
    }

    protected function tagSupportsPattern()
    {
        return method_exists($this, 'testFieldTag_setPattern');
    }

    protected function tagSupportsMaxlength()
    {
        return method_exists($this, 'testFieldTag_setMaxlength');
    }

    protected function tagMethod2Matcher_rules($ruleString = '')
    {

        $explodedRules = explode('|', $ruleString);
        foreach ($explodedRules as $key => $rule) {
            $parameters = [];
            if (Str::contains($rule, ':')) {
                $ruleWithParameters = explode(':', $rule);
                $rule = $ruleWithParameters[0];
                $parameters = explode(',', $ruleWithParameters[1]);
            }

            switch ($rule) {
                case 'accepted':
                case 'required':
                    $this->tagMethod2Matcher_required();
                    break;
                case 'not_numeric':
                    if ($this->tagSupportsPattern()) {
                        $this->tagMethod2Matcher_defaultAttribute('pattern', '\D+');
                    }
                    break;
                case 'url':
                case 'active_url':
                    $this->matchTagAttributes['type'] = 'url';
                    break;
                case 'alpha':
                    if ($this->tagSupportsPattern()) {
                        $this->tagMethod2Matcher_defaultAttribute('pattern', '[a-zA-Z]+');
                    }
                    break;
                case 'alpha_dash':
                    if ($this->tagSupportsPattern()) {
                        $this->tagMethod2Matcher_defaultAttribute('pattern', '[a-zA-Z0-9_\-]+');
                    }
                    break;
                case 'alpha_num':
                    if ($this->tagSupportsPattern()) {
                        $this->tagMethod2Matcher_defaultAttribute('pattern', '[a-zA-Z0-9]+');
                    }
                    break;
                case 'between':
                    if ($this->tag === 'input') {
                        if ($this->matchTagAttributes['type'] === 'number') {
                            $this->tagMethod2Matcher_defaultAttribute('min', $parameters[0]);
                            $this->tagMethod2Matcher_defaultAttribute('max', $parameters[1]);
                        } else {
                            $this->tagMethod2Matcher_defaultAttribute('pattern', $this->matchTagAttributes['pattern'] . '.{' . $parameters[0] . ',' . $parameters[1] . '}');
                            $this->tagMethod2Matcher_defaultAttribute('maxlength', $parameters[1]);
                        }
                    } else if ($this->tagSupportsPattern()) {
                        $this->tagMethod2Matcher_defaultAttribute('maxlength', $parameters[1]);
                    }

                    break;
                case 'in':
                    if ($this->tagSupportsPattern()) {
                        $parameters = (sizeof($parameters) == 1) ? $parameters[0] : '(' . join('|', $parameters) . ')';
                        $this->tagMethod2Matcher_defaultAttribute('pattern', '^' . $parameters . '$');
                    }
                    break;
                case 'ip':
                    //TODO
                    break;
                case 'max':
                    if ($this->tag === 'input') {
                        if ($this->matchTagAttributes['type'] === 'number') {
                            $this->tagMethod2Matcher_defaultAttribute('max', $parameters[0]);
                        } else if ($this->tagSupportsMaxlength()) {
                            $this->tagMethod2Matcher_defaultAttribute('maxlength', $parameters[0]);
                        }
                    } else if ($this->tag === 'textarea') {
                        $this->tagMethod2Matcher_defaultAttribute('maxlength', $parameters[0]);
                    }
                    break;
                case 'min':
                    if ($this->matchTagAttributes['type'] === 'number') {
                        $this->tagMethod2Matcher_defaultAttribute('min', $parameters[0]);
                    } else if ($this->tagSupportsPattern()) {
                        if (isset($this->matchTagAttributes['pattern'])) {
                            $this->tagMethod2Matcher_defaultAttribute('pattern', $this->matchTagAttributes['pattern'] . ".{" . $parameters[0] . ",}");
                        } else {
                            $this->tagMethod2Matcher_defaultAttribute('pattern', ".{" . $parameters[0] . ",}");
                        }
                    }
                    break;
                case 'not_in':
                    if ($this->tagSupportsPattern()) {
                        $this->tagMethod2Matcher_defaultAttribute('pattern', '(?:(?!^' . join('$|^', $parameters) . '$).)*');
                    }
                    break;
                case 'numeric':
                    if ($this->tagSupportsPattern()) {
                        $this->matchTagAttributes['type'] = 'number';
                        $this->tagMethod2Matcher_defaultAttribute('pattern', '[+-]?\d*\.?\d+');
                    }
                    break;
                case 'mimes':
                    if (array_search('jpeg', $parameters) !== false) {
                        array_push($parameters, 'jpg');
                    }
                    $this->tagMethod2Matcher_defaultAttribute('accept', '.' . implode(',.', $parameters));
                    break;

            }
        }
    }

}