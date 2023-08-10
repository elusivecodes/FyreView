<?php
declare(strict_types=1);

namespace Fyre\View\Helpers;

use Fyre\DB\TypeParser;
use Fyre\Entity\Entity;
use Fyre\Form\FormBuilder;
use Fyre\Lang\Lang;
use Fyre\Router\Router;
use Fyre\Security\CsrfProtection;
use Fyre\Server\ServerRequest;
use Fyre\View\Exceptions\FormException;
use Fyre\View\Form\Context;
use Fyre\View\Form\EntityContext;
use Fyre\View\Form\NullContext;
use Fyre\View\Helper;
use Fyre\View\View;

use function array_map;
use function array_pop;
use function explode;
use function get_class;
use function is_array;
use function method_exists;
use function preg_replace;
use function str_replace;
use function str_starts_with;
use function trim;
use function ucwords;

/**
 * FormHelper
 */
class FormHelper extends Helper
{

    protected static array $contextMap = [
        Entity::class => EntityContext::class
    ];

    protected static Context $nullContext;

    protected ServerRequest $request;

    protected Context|null $context = null;

    protected string|null $idPrefix = null;

    /**
     * New Helper constructor.
     * @param View $view The View.
     * @param array $options The helper options.
     */
    public function __construct(View $view, array $options = [])
    {
        parent::__construct($view, $options);

        $this->request = $this->view->getRequest();
    }

    /**
     * Render a button element.
     * @param string $content The button content.
     * @param array $options Options for rendering the button.
     * @return string The button HTML.
     */
    public function button(string $content = '', array $options = []): string
    {
        return FormBuilder::button($content, $options);
    }

    /**
     * Render a checkbox field.
     * @param string $key The field key.
     * @param array $options Options for rendering the checkbox.
     * @return string The checkbox HTML.
     */
    public function checkbox(string $key, array $options = []): string
    {
        $context = $this->getContext();
        $parser = TypeParser::use('boolean');

        $options['id'] ??= $this->getId($key);
        $options['name'] ??= static::getName($key);
        $options['type'] ??= 'checkbox';
        $options['value'] ??= 1;
        $options['checked'] ??= $this->getValue($key, $options);
        $options['required'] ??= $context->isRequired($key);
        $options['hiddenField'] ??= true;

        $options['checked'] = $parser->parse($options['checked']);

        $result = '';

        if ($options['hiddenField'] && $options['name'] !== false) {
            $result .= FormBuilder::hidden(null, [
                'name' => $options['name'],
                'value' => 0
            ]);
        }

        unset($options['hiddenField']);

        $options = static::cleanOptions($options);

        $result .= FormBuilder::input(null, $options);

        return $result;
    }

    /**
     * Render a form close tag.
     * @return string The form close HTML.
     */
    public function close(): string
    {
        $this->context = null;

        return FormBuilder::close();
    }

    /**
     * Render a color input.
     * @param string $key The field key.
     * @param array $options Options for rendering the color input.
     * @return string The color input HTML.
     */
    public function color(string $key, array $options = []): string
    {
        $options['type'] ??= 'color';
        $options['placeholder'] ??= false;

        return $this->text($key, $options);
    }

    /**
     * Render a date input.
     * @param string $key The field key.
     * @param array $options Options for rendering the date input.
     * @return string The date input HTML.
     */
    public function date(string $key, array $options = []): string
    {
        $context = $this->getContext();
        $parser = TypeParser::use('date');

        $options['id'] ??= $this->getId($key);
        $options['name'] ??= static::getName($key);
        $options['placeholder'] ??= static::getLabelText($key);
        $options['type'] ??= 'date';
        $options['value'] ??= $this->getValue($key, $options);
        $options['required'] ??= $context->isRequired($key);

        $value = $parser->parse($options['value']);

        if ($value) {
            $options['value'] = $value
                ->toDateTime()
                ->format('Y-m-d');
        } else {
            unset($options['value']);
        }

        $options = static::cleanOptions($options);

        return FormBuilder::input(null, $options);
    }

    /**
     * Render a datetime input.
     * @param string $key The field key.
     * @param array $options Options for rendering the datetime input.
     * @return string The datetime input HTML.
     */
    public function datetime(string $key, array $options = []): string
    {
        $context = $this->getContext();
        $parser = TypeParser::use('datetime');

        $options['id'] ??= $this->getId($key);
        $options['name'] ??= static::getName($key);
        $options['placeholder'] ??= static::getLabelText($key);
        $options['type'] ??= 'datetime-local';
        $options['value'] ??= $this->getValue($key, $options);
        $options['required'] ??= $context->isRequired($key);

        $value = $parser->parse($options['value']);

        if ($value) {
            $timeZone = $parser->getUserTimeZone();

            if ($timeZone) {
                $value = $value->setTimeZone($timeZone);
            }

            $options['value'] = $value
                ->toDateTime()
                ->format('Y-m-d\TH:i');
        } else {
            unset($options['value']);
        }

        $options = static::cleanOptions($options);

        return FormBuilder::input(null, $options);
    }

    /**
     * Render an email input.
     * @param string $key The field key.
     * @param array $options Options for rendering the email input.
     * @return string The email input HTML.
     */
    public function email(string $key, array $options = []): string
    {
        $options['type'] ??= 'email';

        return $this->text($key, $options);
    }

    /**
     * Render a fieldset close tag.
     * @return string The fieldset close HTML.
     */
    public function fieldsetClose(): string
    {
        return FormBuilder::fieldsetClose();
    }

    /**
     * Render a fieldset open tag.
     * @param array $options Options for rendering the fieldset.
     * @return string The fieldset open HTML.
     */
    public function fieldsetOpen(array $options = []): string
    {
        return FormBuilder::fieldsetOpen($options);
    }

    /**
     * Render a file input.
     * @param string $key The field key.
     * @param array $options Options for rendering the file input.
     * @return string The file input HTML.
     */
    public function file(string $key, array $options = []): string
    {
        $options['type'] ??= 'file';
        $options['value'] = false;
        $options['placeholder'] ??= false;

        return $this->text($key, $options);
    }

    /**
     * Render a hidden input.
     * @param string $key The field key.
     * @param array $options Options for rendering the hidden input.
     * @return string The hidden input HTML.
     */
    public function hidden(string $key, array $options = []): string
    {
        $options['type'] ??= 'hidden';
        $options['placeholder'] ??= false;

        return $this->text($key, $options);
    }

    /**
     * Render an image input.
     * @param string $key The field key.
     * @param array $options Options for rendering the image input.
     * @return string The image input HTML.
     */
    public function image(string $key, array $options = []): string
    {
        $options['type'] ??= 'image';
        $options['value'] = false;
        $options['placeholder'] ??= false;

        return $this->text($key, $options);
    }

    /**
     * Render an input element.
     * @param string $key The field key.
     * @param array $options Options for rendering the input.
     * @return string The input HTML.
     * @throws FormException if the input type is not valid.
     */
    public function input(string $key, array $options = []): string
    {
        $context = $this->getContext();

        $type = $options['type'] ?? $context->getType($key);

        unset($options['type']);

        if (!method_exists($this, $type)) {
            throw FormException::forInvalidInputType($type);
        }

        return $this->$type($key, $options);
    }

    /**
     * Render a label element.
     * @param string $key The field key.
     * @param array $options Options for rendering the label.
     * @return string The label HTML.
     */
    public function label(string $key, array $options = []): string
    {
        $options['for'] ??= $this->getId($key);
        $options['text'] ??= static::getLabelText($key);

        $text = $options['text'];

        unset($options['text']);

        if ($text === false) {
            $text = '';
        }

        if ($options['for'] === false) {
            unset($options['for']);
        }

        return FormBuilder::label($text, $options);
    }

    /**
     * Render a legend element.
     * @param string $content The legend content.
     * @param array $options Options for rendering the legend.
     * @return string The legend HTML.
     */
    public function legend(string $content = '', array $options = []): string
    {
        return FormBuilder::legend($content, $options);
    }

    /**
     * Render a month input.
     * @param string $key The field key.
     * @param array $options Options for rendering the month input.
     * @return string The month input HTML.
     */
    public function month(string $key, array $options = []): string
    {
        $options['type'] ??= 'month';

        return $this->text($key, $options);
    }

    /**
     * Render a number input.
     * @param string $key The field key.
     * @param array $options Options for rendering the number input.
     * @return string The number input HTML.
     */
    public function number(string $key, array $options = []): string
    {
        $context = $this->getContext();
        $parser = TypeParser::use('float');

        $options['id'] ??= $this->getId($key);
        $options['name'] ??= static::getName($key);
        $options['placeholder'] ??= static::getLabelText($key);
        $options['type'] ??= 'number';
        $options['value'] ??= $this->getValue($key, $options);
        $options['min'] ??= $context->getMin($key);
        $options['max'] ??= $context->getMax($key);
        $options['step'] ??= $context->getStep($key);
        $options['required'] ??= $context->isRequired($key);

        if ($options['value'] !== false) {
            $options['value'] = $parser->parse($options['value']);
        }

        $options = static::cleanOptions($options);

        return FormBuilder::input(null, $options);
    }

    /**
     * Render a form open tag.
     * @param object|null $item The context item.
     * @param array $options Options for rendering the form.
     * @return string The form open HTML.
     * @throws FormException if there is an unclosed form or the context is not valid.
     */
    public function open(object|null $item = null, array $options = []): string
    {
        if ($this->context) {
            throw FormException::forUnclosedForm();
        }

        if ($item) {
            $class = get_class($item);
            $className = static::$contextMap[$class] ?? null;

            if ($className === null) {
                throw FormException::forInvalidContext();
            }

            $this->context = new $className($item);
        } else {
            $this->context = static::getNullContext();
        }

        $options['action'] ??= null;
        $options['idPrefix'] ??= null;

        if (!$options['action']) {
            $options['action'] = (string) $this->request->getUri();
        } else if (is_array($options['action'])) {
            $options['action'] = Router::build($options['action']);
        }

        if ($options['idPrefix']) {
            $this->idPrefix = $options['idPrefix'];
        }

        unset($options['idPrefix']);

        $html = FormBuilder::open('', $options);

        if (CsrfProtection::isEnabled()) {
            $html .= FormBuilder::hidden(null, [
                'name' => CsrfProtection::getField(),
                'value' => CsrfProtection::getTokenHash()
            ]);
        }

        return $html;
    }

    /**
     * Render a multipart form open tag.
     * @param mixed $item The context item.
     * @param array $options Options for rendering the form.
     * @return string The form open HTML.
     */
    public function openMultipart(mixed $item = null, array $options = []): string
    {
        $options['enctype'] = 'multipart/form-data';

        return $this->open($item, $options);
    }

    /**
     * Render a password input.
     * @param string $key The field key.
     * @param array $options Options for rendering the password input.
     * @return string The password input HTML.
     */
    public function password(string $key, array $options = []): string
    {
        $options['type'] ??= 'password';
        $options['value'] = false;

        return $this->text($key, $options);
    }

    /**
     * Render a radio input.
     * @param string $key The field key.
     * @param array $options Options for rendering the radio input.
     * @return string The radio input HTML.
     */
    public function radio(string $key, array $options = []): string
    {
        $context = $this->getContext();
        $parser = TypeParser::use('boolean');

        $options['id'] ??= $this->getId($key);
        $options['name'] ??= static::getName($key);
        $options['type'] ??= 'radio';
        $options['value'] ??= false;

        if ($options['value'] !== false) {
            $options['checked'] ??= $this->getValue($key, $options) == $options['value'];
        } else {
            $options['checked'] = false;
        }

        $options['required'] ??= $context->isRequired($key);

        $options['checked'] = $parser->parse($options['checked']);

        $options = static::cleanOptions($options);

        return FormBuilder::input(null, $options);
    }

    /**
     * Render a range input.
     * @param string $key The field key.
     * @param array $options Options for rendering the range input.
     * @return string The range input HTML.
     */
    public function range(string $key, array $options = []): string
    {
        $options['type'] ??= 'range';
        $options['placeholder'] ??= false;

        return $this->text($key, $options);
    }

    /**
     * Render a reset input.
     * @param string $key The field key.
     * @param array $options Options for rendering the reset input.
     * @return string The reset input HTML.
     */
    public function reset(string $key, array $options = []): string
    {
        $options['type'] ??= 'reset';
        $options['placeholder'] ??= false;

        return $this->text($key, $options);
    }

    /**
     * Render a search input.
     * @param string $key The field key.
     * @param array $options Options for rendering the search input.
     * @return string The search input HTML.
     */
    public function search(string $key, array $options = []): string
    {
        $options['type'] ??= 'search';

        return $this->text($key, $options);
    }

    /**
     * Render a select element.
     * @param string $key The field key.
     * @param array $options Options for rendering the select.
     * @return string The select HTML.
     */
    public function select(string $key, array $options = []): string
    {
        $context = $this->getContext();

        $options['id'] ??= $this->getId($key);
        $options['name'] ??= static::getName($key);
        $options['value'] ??= $this->getValue($key, $options);
        $options['required'] ??= $context->isRequired($key);
        $options['options'] ??= null;

        $options['value'] ??= [];
        $options['value'] = (array) $options['value'];

        if ($options['options'] === null && $options['value'] !== []) {
            $options['options'] = array_map(
                fn(mixed $value): array => [
                    'value' => $value,
                    'label' => ''
                ],
                $options['value']
            );
        } else {
            $options['options'] ??= [];
        }

        $options = static::cleanOptions($options);

        return FormBuilder::select(null, $options);
    }

    /**
     * Render a multiple select element.
     * @param string $key The field key.
     * @param array $options Options for rendering the select.
     * @return string The select HTML.
     */
    public function selectMulti(string $key, array $options = []): string
    {
        $options['multiple'] ??= true;

        return $this->select($key, $options);
    }

    /**
     * Render a submit input.
     * @param string $key The field key.
     * @param array $options Options for rendering the submit input.
     * @return string The submit input HTML.
     */
    public function submit(string $key, array $options = []): string
    {
        $options['type'] ??= 'submit';
        $options['placeholder'] ??= false;

        return $this->text($key, $options);
    }

    /**
     * Render a telephone input.
     * @param string $key The field key.
     * @param array $options Options for rendering the telephone input.
     * @return string The telephone input HTML.
     */
    public function tel(string $key, array $options = []): string
    {
        $options['type'] ??= 'tel';

        return $this->text($key, $options);
    }

    /**
     * Render a text input.
     * @param string $key The field key.
     * @param array $options Options for rendering the text input.
     * @return string The text input HTML.
     */
    public function text(string $key, array $options = []): string
    {
        $context = $this->getContext();
        $parser = TypeParser::use('string');

        $options['id'] ??= $this->getId($key);
        $options['name'] ??= static::getName($key);
        $options['placeholder'] ??= static::getLabelText($key);
        $options['type'] ??= 'text';
        $options['value'] ??= $this->getValue($key, $options);
        $options['required'] ??= $context->isRequired($key);
        $options['maxlength'] ??= $context->getMaxLength($key);

        if ($options['value'] !== false) {
            $options['value'] = $parser->parse($options['value']);
        }

        $options = static::cleanOptions($options);

        return FormBuilder::input(null, $options);
    }

    /**
     * Render a textarea element.
     * @param string $key The field key.
     * @param array $options Options for rendering the textarea.
     * @return string The textarea HTML.
     */
    public function textarea(string $key, array $options = []): string
    {
        $context = $this->getContext();
        $parser = TypeParser::use('string');

        $options['id'] ??= $this->getId($key);
        $options['name'] ??= static::getName($key);
        $options['placeholder'] ??= static::getLabelText($key);
        $options['value'] ??= $this->getValue($key, $options);
        $options['required'] ??= $context->isRequired($key);
        $options['maxlength'] ??= $context->getMaxLength($key);

        if ($options['value'] !== false) {
            $options['value'] = $parser->parse($options['value']);
        }

        $options = static::cleanOptions($options);

        return FormBuilder::textarea(null, $options);
    }

    /**
     * Render a time input.
     * @param string $key The field key.
     * @param array $options Options for rendering the time input.
     * @return string The time input HTML.
     */
    public function time(string $key, array $options = []): string
    {
        $context = $this->getContext();
        $parser = TypeParser::use('time');

        $options['id'] ??= $this->getId($key);
        $options['name'] ??= static::getName($key);
        $options['placeholder'] ??= static::getLabelText($key);
        $options['type'] ??= 'time';
        $options['value'] ??= $this->getValue($key, $options);
        $options['required'] ??= $context->isRequired($key);

        $value = $parser->parse($options['value']);

        if ($value) {
            $options['value'] = $value
                ->toDateTime()
                ->format('H:i');
        } else {
            unset($options['value']);
        }

        $options = static::cleanOptions($options);

        return FormBuilder::input(null, $options);
    }

    /**
     * Render a url input.
     * @param string $key The field key.
     * @param array $options Options for rendering the url input.
     * @return string The url input HTML.
     */
    public function url(string $key, array $options = []): string
    {
        $options['type'] ??= 'url';

        return $this->text($key, $options);
    }

    /**
     * Render a week input.
     * @param string $key The field key.
     * @param array $options Options for rendering the week input.
     * @return string The week input HTML.
     */
    public function week(string $key, array $options = []): string
    {
        $options['type'] ??= 'week';

        return $this->text($key, $options);
    }

    /**
     * Get the form Context.
     * @return Context The form Context.
     */
    protected function getContext(): Context
    {
        return $this->context ?? static::getNullContext();
    }

    /**
     * Get a field ID.
     * @param string $key The field key.
     * @return string The field ID.
     */
    protected function getId(string $key): string
    {
        if ($this->idPrefix) {
            $key = $this->idPrefix.'.'.$key;
        }

        return preg_replace('/(?<!\.)[._]([^._]+)/', '-\1', $key);
    }

    /**
     * Get a value from post data.
     * @param string $name The field name.
     * @return mixed The value.
     */
    protected function getPostValue(string $name): mixed
    {
        $key = static::getKey($name);

        return $this->request->getPost($key);
    }

    /**
     * Get a field value.
     * @param string $key The field key.
     * @return mixed The value.
     */
    protected function getValue(string $key, array $options): mixed
    {
        $context = $this->getContext();
        $options['name'] ??= null;

        $value = null;

        if ($options['name']) {
            $value = $this->getPostValue($options['name']);
        }

        $value ??= $context->getValue($key);
        $value ??= $options['default'] ?? null;
        $value ??= $context->getDefaultValue($key);

        return $value;
    }

    /**
     * Clean the input options.
     * @param arary $options The input options.
     * @return array The input options.
     */
    protected static function cleanOptions(array $options): array
    {
        foreach ($options AS $key => $value) {
            if ($value !== false || str_starts_with($key, 'data-')) {
                continue;
            }

            unset($options[$key]);
        }

        unset($options['default']);

        return $options;
    }

    /**
     * Get a field key.
     * @param string $name The field name.
     * @return string The field key.
     */
    protected static function getKey(string $name): string
    {
        $key = preg_replace('/\[(.*?)\]/', '.\1', $name);
        $key = trim($key, '.');

        return $key;
    }

    /**
     * Get a field label text.
     * @param string $key The field key.
     * @return string The field label text.
     */
    protected static function getLabelText(string $key): string
    {
        $parts = explode('.', $key);
        $field = array_pop($parts);

        $label = Lang::get('Form.'.$field);

        if ($label) {
            return $label;
        }

        $label = str_replace('_', ' ', $field);
        $label = ucwords($label);

        return $label;
    }

    /**
     * Get a field name.
     * @param string $key The field key.
     * @return string The field name.
     */
    protected static function getName(string $key): string
    {
        return preg_replace('/(?<!\.)\.([^.]+)/', '[\1]', $key);
    }

    /**
     * Get the NullContext.
     * @return Context The NullContext.
     */
    protected static function getNullContext(): Context
    {
        return static::$nullContext ??= new NullContext;
    }

}
