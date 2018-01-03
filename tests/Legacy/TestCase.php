<?php

namespace FormBuilderTests\Legacy;

use DOMDocument;
use DOMElement;
use Gajus\Dindent\Indenter;
use FormBuilderTests\Legacy\Traits\AppliesTagMethodsToMatcherData;
use FormBuilderTests\Legacy\Traits\Tests\TagTests;
use Illuminate\Contracts\Console\Kernel;
use Nicat\FormBuilder\FormBuilderFacade;
use Nicat\FormBuilder\FormBuilderServiceProvider;
use Nicat\HtmlBuilder\HtmlBuilderFacade;
use Nicat\HtmlBuilder\HtmlBuilderServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use PHPUnit\Framework\ExpectationFailedException;
use Session;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

class TestCase extends BaseTestCase
{

    use AppliesTagMethodsToMatcherData, TagTests;

    protected $config = [


        /*
        |--------------------------------------------------------------------------
        | Translation key to use for automatic translations.
        |--------------------------------------------------------------------------
        |
        | By default FormBuilder tries to automatically translate
        | labels, placeholders and help-texts using this base translation-key.
        |
         */
        'translations' => 'validation.attributes',

        /*
        |--------------------------------------------------------------------------
        | 'Honeypot anti-bot protection.
        |--------------------------------------------------------------------------
        |
        | Settings regarding anti-bot protection of forms using a honeypot-field.
        |
         */
        'honeypot' => [

            /*
             * Whether honeypot-protection should be enabled at all.
             */
            'enabled' => true,

        ],

        /*
        |--------------------------------------------------------------------------
        | Time-limit anti-bot protection.
        |--------------------------------------------------------------------------
        |
        | Settings regarding anti-bot protection of forms using a time-limit.
        |
         */
        'time_limit' => [

            /*
             * Whether time-limit-protection should be enabled at all.
             */
            'enabled' => true,

            /*
             * The default-limit (in seconds) to be used.
             * (Can be overridden explicitly per request via the first parameter of the 'timeLimit'-rule of the request-object.)
             */
            'default_limit' => 2,

        ],

        /*
        |--------------------------------------------------------------------------
        | Captcha anti-bot protection.
        |--------------------------------------------------------------------------
        |
        | Settings regarding anti-bot protection of forms using a captcha-field.
        |
         */
        'captcha' => [

            /*
             * Whether captcha-protection should be enabled at all.
             */
            'enabled' => true,

            /*
             * The number of times a form can be submitted, before a captcha is required.
             * (0 means, the captcha is shown always.)
             * (Can be overridden explicitly per request via the first parameter of the 'captcha'-rule of the request-object.)
             */
            'default_limit' => 2,

            /*
             * The time-span (in minutes) for which the captcha-limit is valid.
             * After reaching the limit for captcha-less submissions, it takes this long,
             * before the user can submit the form again without a captcha.
             * (Can be overridden explicitly per request via the second parameter of the 'captcha'-rule of the request-object.)
             */
            'decay_time' => 2,

        ],

        /*
        |--------------------------------------------------------------------------
        | Ajax-validation.
        |--------------------------------------------------------------------------
        |
        | Settings regarding ajax-validation.
        |
         */
        'ajax_validation' => [

            /*
             * Whether ajax-validation should be enabled at all.
             */
            'enabled' => true,

            /*
             * Should an ajax-validation on form-submission be enabled by default for every form?
             * (Can be overridden explicitly per form by setting the 'ajaxValidation' option
             * of the Form::open call to 'onSubmit' or false.)
             */
            'enable_on_form_submit_by_default' => false,

        ]
    ];

    protected $tag = '';

    protected $tagFunction = '';

    protected $tagParameters = [];

    protected $tagMethods = [];

    protected $matchTagAttributes = [];

    protected $html = '';

    protected $matchTagChildren = [];

    protected $matcher = [];


    protected function getPackageProviders($app)
    {
        return [
            HtmlBuilderServiceProvider::class,
            FormBuilderServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Html' => HtmlBuilderFacade::class,
            'Form' => FormBuilderFacade::class,
        ];
    }

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->app['request']->setLaravelSession($this->app['session']->driver('array'));

    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('htmlbuilder', $this->config);
    }

    protected function generateTag()
    {

        $methods = get_class_methods($this);
        foreach ($methods as $key => $method) {
            if (strpos($method, 'modifyTag_') === 0) {
                $this->$method();
            }
        }

        // Generate the Tag.
        $this->html = $this->callFormBuilderFunction($this->tagFunction, $this->tagParameters, $this->tagMethods)->generate();

    }

    protected function setUpMatcherData()
    {

        $methods = get_class_methods($this);
        foreach ($methods as $key => $method) {
            if (strpos($method, 'matcherModifier_') === 0) {
                $this->$method();
            }
        }

    }

    protected function applyTagMethods2MatcherData()
    {

        // Apply methods
        if (isset($this->tagMethods) && (count($this->tagMethods) > 0)) {
            foreach ($this->tagMethods as $key => $methodData) {

                if (method_exists($this, 'tagMethod2Matcher_' . $methodData['name'])) {

                    call_user_func_array(
                        [
                            $this,
                            'tagMethod2Matcher_' . $methodData['name']
                        ],
                        $methodData['parameters']
                    );

                } else {
                    $this->tagMethod2Matcher_defaultAttribute($methodData['name'], $methodData['parameters'][0]);
                }
            }
        }

    }

    protected function generateMatcher()
    {

        foreach ($this->matchTagAttributes as $attribute => $value) {
            if ($value === false) {
                unset($this->matchTagAttributes[$attribute]);
            }
        }

        $this->matcher = [
            [
                'tag' => $this->tag,
                'attributes' => $this->matchTagAttributes,
                'children' => $this->matchTagChildren
            ]
        ];
    }

    protected function performTagTest()
    {

        $this->generateTag();

        $this->setUpMatcherData();

        $this->applyTagMethods2MatcherData();

        $this->generateMatcher();

        // Load $this->html to dom-object.
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($this->html);
        if (count(libxml_get_errors()) > 0) {
            foreach (libxml_get_errors() as $error) {
                throw new ExpectationFailedException(
                    'HTML-Syntax Error:\n' . $error->message . "in: \n" . $this->html
                );
            }

            libxml_clear_errors();
        }

        // Get body-node of dom-object.
        $body = $dom->getElementsByTagName('body')->item(0);

        // Perform html-node-assertion.
        foreach ($this->matcher as $childKey => $childMatcher) {
            $this->assertHtmlNode($body, $childKey, $childMatcher);
        }

    }


    /**
     * @param string $function
     * @param array $parameters
     * @param array $methods
     * @return mixed
     */
    protected function callFormBuilderFunction($function = '', $parameters = [], $methods = [])
    {

        $functionParameters = [];
        if (count($parameters) > 0) {
            foreach ($parameters as $parameterKey => $parameterValue) {
                $functionParameters[] = $parameterValue;
            }
        }

        $tag = call_user_func_array(
            'Form::' . $function, $functionParameters
        );

        if (count($methods) > 0) {
            foreach ($methods as $methodKey => $methodData) {
                $tag = call_user_func_array(
                    [
                        $tag,
                        $methodData['name']
                    ],
                    $methodData['parameters']
                );
            }
        }

        return $tag;
    }

    protected function generateErrorMsg($text = '')
    {

        $headerBefore = "\n\n==================\n";
        $headerAfter = "\n==================\n";

        $humanReadableMatcher = '';
        (new CliDumper())->dump(
            (new VarCloner)->cloneVar($this->matcher),
            function ($line, $depth) use (&$humanReadableMatcher) {
                // A negative depth means "end of dump"
                if ($depth >= 0) {
                    // Adds a two spaces indentation to the line
                    $humanReadableMatcher .= str_repeat('  ', $depth) . $line . "\n";
                }
            }
        );

        $text =
            $headerBefore .
            'Error:' .
            $headerAfter .
            $text .
            $headerBefore .
            'Generated HTML:' .
            $headerAfter .
            (new Indenter())->indent($this->html) .
            $headerBefore .
            'Matched Structure:' .
            $headerAfter .
            $humanReadableMatcher;

        return $text;
    }

    protected function assertHtmlNode(DOMElement $parentNode, $key = 0, $matcher = [])
    {
        $nodePath = $parentNode->getNodePath();

        // The human readable representation of the tested node - used in error messages.
        if (array_key_exists('text', $matcher)) {
            $humanReadableNode = 'Plain text "' . $matcher['text'] . '" (child-node number ' . ($key + 1) . ' at path "' . $nodePath . '")';
            $matcher['tag'] = '#text';
        } else {

            $humanReadableNode = 'Tag of type "' . $matcher['tag'] . '" (child-node number ' . ($key + 1) . ' at path "' . $nodePath . '")';
        }

        // Get the node, that should be checked.
        $node = $parentNode->childNodes->item($key);

        // Assert, that the node is present at all at the desired location.
        $this->assertNotNull(
            $node,
            $this->generateErrorMsg($humanReadableNode . ' could not be found. No child was found at this location at all.')
        );

        // Assert, that the tag-type is the desired one.
        $this->assertEquals(
            $node->nodeName,
            $matcher['tag'],
            $this->generateErrorMsg($humanReadableNode . ' could not be found. A "' . $node->nodeName . '" was found at this location instead.')
        );

        // Check Attributes.
        if (isset($matcher['attributes']) && (count($matcher['attributes']) > 0)) {

            $nodeAttributes = [];
            $attributeCount = $node->attributes->length;
            for ($i = 0; $i < $attributeCount; ++$i) {
                $nodeAttributes[$node->attributes->item($i)->name] = $node->attributes->item($i)->value;
            }

            foreach ($matcher['attributes'] as $attributeName => $attributeValue) {

                // Assert, that the node indeed has the attribute.
                $this->assertArrayHasKey(
                    $attributeName,
                    $nodeAttributes,
                    $this->generateErrorMsg($humanReadableNode . ' should have the attribute "' . $attributeName . '". But it has not.')
                );

                if ($attributeName === 'class') {

                    $desiredClasses = explode(' ', $attributeValue);

                    $presentClasses = explode(' ', $nodeAttributes[$attributeName]);

                    foreach ($desiredClasses as $key => $desiredClass) {

                        // Assert, that the desired class is indeed present.
                        $this->assertNotFalse(
                            array_search($desiredClass, $presentClasses),
                            $this->generateErrorMsg($humanReadableNode . ' should have a class called "' . $desiredClass . '". But it has not.')
                        );

                        unset($presentClasses[array_search($desiredClass, $presentClasses)]);
                    }

                    // Assert, that the node has no superfluous classes.
                    $this->assertEmpty(
                        $presentClasses,
                        $this->generateErrorMsg($humanReadableNode . ' should not have the class "' . current($presentClasses) . '". But it has.')
                    );

                } else if ($attributeValue !== true) {
                    // Assert, that the attribute has the desired value (only if $attributeValue is not true = boolean attribute).
                    $this->assertEquals(
                        $nodeAttributes[$attributeName],
                        $attributeValue,
                        $this->generateErrorMsg($humanReadableNode . ' should have an attribute "' . $attributeName . '" with the value "' . $attributeValue . '". But the value is "' . $nodeAttributes[$attributeName] . '" instead.')
                    );
                }


                // Unset the successfully checked attribute from $nodeAttributes,
                // so we can later check for superfluous attributes.
                unset($nodeAttributes[$attributeName]);
            }

            // Assert, that the node has no superfluous attributes.
            $this->assertEmpty(
                $nodeAttributes,
                $this->generateErrorMsg($humanReadableNode . ' should not have the attribute "' . key($nodeAttributes) . '". But it has.')
            );

        }

        // Assert, that the node has no attributes at all, if this should be the case.
        if (!isset($matcher['attributes']) || ((isset($matcher['attributes'])) && (count($matcher['attributes']) === 0))) {
            $this->assertFalse(
                $node->hasAttributes(),
                $this->generateErrorMsg($humanReadableNode . ' should have no attributes at all, but it has.')
            );
        }

        // Assert, that the plain text is identical (only for nodes which contain plain-text and no tags..
        if (isset($matcher['text'])) {
            $this->assertEquals(
                $matcher['text'],
                $node->wholeText,
                $this->generateErrorMsg($humanReadableNode . ' has an unexpected text: "' . $node->wholeText . '".')
            );
        }

        // Assert, that no children are present at all, if none are desired.
        if (!isset($matcher['children']) || (count($matcher['children']) === 0)) {
            $this->assertFalse(
                $node->hasChildNodes(),
                $this->generateErrorMsg($humanReadableNode . ' should have no children at all, but it has.')
            );
        }

        // Assert, that the child-count is the desired one.
        $desiredChildCount = 0;
        if (isset($matcher['children']) && (count($matcher['children']) > 0)) {
            $desiredChildCount = count($matcher['children']);
        }
        $actualChildCount = 0;
        if (is_a($node->childNodes, 'DOMNodeList')) {
            $actualChildCount = $node->childNodes->length;
        }
        $this->assertEquals(
            $desiredChildCount,
            $actualChildCount,
            $this->generateErrorMsg($humanReadableNode . ' should have ' . $desiredChildCount . ' children, but it has ' . $actualChildCount . ' instead.')
        );


        // If the node should have children, we assert those also recursively.
        if (isset($matcher['children']) && (count($matcher['children']) > 0)) {
            foreach ($matcher['children'] as $childKey => $childMatcher) {
                $this->assertHtmlNode($node, $childKey, $childMatcher);
            }
        }

    }

    protected function tagMethodExists($name = '', $parameters = [])
    {
        if (isset($this->tagMethods) && (count($this->tagMethods) > 0)) {
            foreach ($this->tagMethods as $key => $methodData) {
                if (($methodData['name'] === $name) && ($methodData['parameters'] === $parameters)) {
                    return true;
                }
            }
        }
    }


}