<?php
declare(strict_types=1);

namespace Fyre\View;

use Fyre\Container\Container;
use Fyre\Utility\Path;
use Fyre\View\Exceptions\ViewException;
use Fyre\View\Traits\EvaluateTrait;
use Fyre\View\Traits\ViewVarsTrait;
use ReflectionClass;

use function method_exists;
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

    protected Container $container;

    protected HelperRegistry $helperRegistry;

    protected array $helpers = [];

    protected string|null $template = null;

    protected TemplateLocator $templateLocator;

    protected View $view;

    /**
     * New Cell constructor.
     *
     * @param Container $container The Container.
     * @param TemplateLocator $templateLocator The TemplateLocator.
     * @param HelperRegistry $helperRegistry The HelperRegistry.
     * @param View $view The View.
     * @param array $options The cell options.
     */
    public function __construct(Container $container, TemplateLocator $templateLocator, HelperRegistry $helperRegistry, View $view, array $options = [])
    {
        $this->container = $container;
        $this->templateLocator = $templateLocator;
        $this->helperRegistry = $helperRegistry;
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
        $this->helpers[$name] ??= $this->helperRegistry->build($name, $this->view, $options);

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

        if (!method_exists($this, $this->action)) {
            throw ViewException::forInvalidCellMethod($cell, $this->action);
        }

        $this->container->call([$this, $this->action], $this->args);

        $template = $this->template;

        if ($template === null) {
            $file = TemplateLocator::normalize($this->action);
            $template = Path::join($cell, $file);
        }

        $filePath = $this->templateLocator->locate($template, TemplateLocator::CELLS_FOLDER);

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
