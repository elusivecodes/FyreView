<?php
declare(strict_types=1);

namespace Fyre\View\Traits;

use function array_merge;

/**
 * ViewVarsTrait
 */
trait ViewVarsTrait
{

    protected array $data = [];

    /**
     * Get the view data.
     * @return array The view data.
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Set a view data value.
     * @param string $name The data name.
     * @param mixed $value The data value.
     * @return View The View.
     */
    public function set(string $name, mixed $value): static
    {
        $this->data[$name] = $value;

        return $this;
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

}
