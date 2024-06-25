<?php
declare(strict_types=1);

namespace Fyre\View;

use Fyre\Utility\Path;
use Fyre\View\Exceptions\ViewException;
use Fyre\View\Traits\EvaluateTrait;
use Fyre\View\Traits\ViewVarsTrait;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

use function preg_replace;

/**
 * Cell
 */
abstract class Cell
{
    use EvaluateTrait;
    use ViewVarsTrait;

    protected string $action;

    protected array $args;

    protected array $helpers = [];

    protected string|null $template = null;

    protected View $view;

    /**
     * New Cell constructor.
     *
     * @param View $view The View.
     * @param array $options The cell options.
     */
    public function __construct(View $view, array $options = [])
    {
        $this->view = $view;

        $this->action = $options['action'] ?? 'display';
        $this->args = $options['args'] ?? [];
    }

    /**
     * Load a helper.
     *
     * @param string $name The helper name.
     * @return Helper The Helper.
     */
    public function __get(string $name): Helper
    {
        $this->loadHelper($name);

        return $this->helpers[$name];
    }

    /**
     * Render the cell as a string.
     *
     * @return string The rendered cell.
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Get the template.
     *
     * @return string|null The template.
     */
    public function getTemplate(): string|null
    {
        return $this->template;
    }

    /**
     * Get the View.
     *
     * @return View The View.
     */
    public function getView(): View
    {
        return $this->view;
    }

    /**
     * Load a Helper.
     *
     * @param string $name The helper name.
     * @param array $options The helper options.
     * @return Cell The Cell.
     */
    public function loadHelper(string $name, array $options = []): static
    {
        $this->helpers[$name] ??= HelperRegistry::load($name, $this->view, $options);

        return $this;
    }

    /**
     * Render the cell.
     *
     * @return string The rendered cell.
     *
     * @throws ViewException if the method or template is not valid.
     */
    public function render(): string
    {
        $cell = preg_replace('/Cell$/', '', (new ReflectionClass($this))->getShortName());

        try {
            $method = new ReflectionMethod($this, $this->action);
            $method->invokeArgs($this, $this->args);
        } catch (ReflectionException $e) {
            throw ViewException::forInvalidCellMethod($cell, $this->action);
        }

        $template = $this->template;

        if ($template === null) {
            $file = Template::normalize($this->action);
            $template = Path::join($cell, $file);
        }

        $filePath = Template::locate($template, Template::CELLS_FOLDER);

        if (!$filePath) {
            throw ViewException::forInvalidTemplate($template);
        }

        return $this->evaluate($filePath, $this->data);
    }

    /**
     * Set the template file.
     *
     * @param string $file The template file.
     * @return Cell The Cell.
     */
    public function setTemplate(string $file): static
    {
        $this->template = $file;

        return $this;
    }
}
