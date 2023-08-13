<?php
declare(strict_types=1);

namespace Fyre\View;

use Fyre\Server\ServerRequest;
use Fyre\View\Exceptions\ViewException;

use function array_merge;
use function array_pop;
use function extract;
use function func_get_arg;
use function ob_end_clean;
use function ob_get_contents;
use function ob_start;

/**
 * View
 */
class View
{

    protected ServerRequest $request;

    protected array $data = [];

    protected string $content = ''; 

    protected string|null $file = null; 

    protected string|null $layout = 'default';

    protected array $blocks = [];

    protected array $blockStack = [];

    protected array $helpers = [];

    /**
     * New View constructor.
     * @param ServerRequest $request The ServerRequest.
     */
    public function __construct(ServerRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Load a helper.
     * @param string $name The helper name.
     */
    public function __get(string $name)
    {
        $this->loadHelper($name);

        return $this->helpers[$name];
    }

    /**
     * Append content to a block.
     * @param string $name The block name.
     * @return View The View.
     */
    public function append(string $name): static
    {
        return $this->start($name, 'append');
    }

    /**
     * Assign content to a block.
     * @param string $name The block name.
     * @param string $content The block content.
     * @return View The View.
     */
    public function assign(string $name, string $content): static
    {
        $this->blocks[$name] = $content;

        return $this;
    }

    /**
     * Get the layout content.
     * @return string The layout content.
     */
    public function content(): string
    {
        return $this->content;
    }

    /**
     * Render an element.
     * @param string $file The element file.
     * @return string The rendered element.
     * @throws ViewException if the element is not valid.
     */
    public function element(string $file, array $data = []): string
    {
        $filePath = Template::locate($file, Template::ELEMENTS_FOLDER);

        if (!$filePath) {
            throw ViewException::forInvalidElement($file);
        }

        return $this->evaluate($filePath, $data);
    }

    /**
     * End a block.
     * @return View The View.
     * @throws ViewException if a block is not opened.
     */
    public function end(): static
    {
        $block = array_pop($this->blockStack);

        if (!$block) {
            throw ViewException::forUnopenedBlock();
        }

        $contents =  ob_get_contents();

        ob_end_clean();

        $name = $block['name'];

        $this->blocks[$name] ??= '';

        switch ($block['type']) {
            case 'append':
                $this->blocks[$name] .= $contents;
                break;
            case 'prepend':
                $this->blocks[$name] = $contents.$this->blocks[$name];
                break;
            default:
                $this->blocks[$name] = $contents;
                break;
        }

        return $this;
    }

    /**
     * Fetch a block.
     * @param string $name The block name.
     * @param string $default The default value.
     * @return string The block contents.
     */
    public function fetch(string $name, string $default = ''): string
    {
        return $this->blocks[$name] ?? $default;
    }

    /**
     * Get the view data.
     * @return array The view data.
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Get the layout.
     * @return string|null The layout.
     */
    public function getLayout(): string|null
    {
        return $this->layout;
    }

    /**
     * Get the ServerRequest.
     * @return ServerRequest The ServerRequest.
     */
    public function getRequest(): ServerRequest
    {
        return $this->request;
    }

    /**
     * Load a Helper.
     * @param string $name The helper name.
     * @param array $options The helper options.
     * @return View The View.
     */
    public function loadHelper(string $name, array $options = []): static
    {
        $this->helpers[$name] ??= HelperRegistry::load($name, $this, $options);

        return $this;
    }

    /**
     * Prepend content to a block.
     * @param string $name The block name.
     * @return View The View.
     */
    public function prepend(string $name): static
    {
        return $this->start($name, 'prepend');
    }

    /**
     * Render a template.
     * @param string $file The template file.
     * @return string The rendered template.
     * @throws ViewException if the template is not valid or there are unclosed blocks.
     */
    public function render(string $file): string
    {
        $filePath = Template::locate($file);
        $layoutPath = Template::locate($this->layout, Template::LAYOUTS_FOLDER);

        if (!$filePath) {
            throw ViewException::forInvalidTemplate($file);
        }

        $this->content = $this->evaluate($filePath, $this->data);

        if (!$layoutPath) {
            $result = $this->content;
        } else {
            $result = $this->evaluate($layoutPath, $this->data);
        }

        if ($this->blockStack !== []) {
            while ($this->blockStack !== []) {
                $this->end();
            }

            throw ViewException::forUnclosedBlock();
        }

        $this->blocks = [];

        return $result;
    }

    /**
     * Reset content of a block.
     * @param string $name The block name.
     * @return View The View.
     */
    public function reset(string $name): static
    {
        return $this->assign($name, '');
    }

    /**
     * Set view data.
     * @param array $data The view data.
     * @return View The View.
     */
    public function setData(array $data): static
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * Set the layout.
     * @param string|null $layout The layout.
     * @return View The View.
     */
    public function setLayout(string|null $layout): static
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Start content for a block.
     * @param string $name The block name.
     * @param string|null $type The block type.
     * @return View The View.
     */
    public function start(string $name, string|null $type = null): static
    {
        ob_start();

        $this->blockStack[] = [
            'name' => $name,
            'type' => $type
        ];

        return $this;
    }

    /**
     * Render and inject data into a file.
     * @param string $filePath The file path.
     * @param array $data The data to inject.
     * @return string The rendered file.
     */
    protected function evaluate(string $filePath, array $data): string
    {
        extract($data);

        try {
            ob_start();

            include func_get_arg(0);

            return ob_get_contents();
        } finally {
            ob_end_clean();
        }
    }

}
