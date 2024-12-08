<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Sqlite;

use Fyre\Validation\Rule;

trait IntegerTestTrait
{
    public function testIntegerBetweenValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value INTEGER NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $this->validator->add('value', Rule::between(100, 1000));

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="100" max="1000" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testIntegerEntityValue(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value INTEGER NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $entity = $this->model->newEntity([
            'value' => 999,
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" value="999" placeholder="Value" min="-2147483648" max="2147483647" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testIntegerGreaterThanOrEqualsValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value INTEGER NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $this->validator->add('value', Rule::greaterThanOrEquals(100));

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="100" max="2147483647" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testIntegerGreaterThanValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value INTEGER NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $this->validator->add('value', Rule::greaterThan(100));

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="101" max="2147483647" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testIntegerLessThanOrEqualsValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value INTEGER NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $this->validator->add('value', Rule::lessThanOrEquals(1000));

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="-2147483648" max="1000" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testIntegerLessThanValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value INTEGER NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $this->validator->add('value', Rule::lessThan(1000));

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="-2147483648" max="999" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testIntegerMinMaxSchema(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value INTEGER NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="-2147483648" max="2147483647" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testIntegerRequiredValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value INTEGER NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $this->validator->add('value', Rule::required());

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="-2147483648" max="2147483647" step="1" required />',
            $this->view->Form->input('value')
        );
    }

    public function testIntegerSchemaDefaultValue(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value INTEGER NOT NULL DEFAULT 999,
                PRIMARY KEY (id)
            )
        EOT);

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" value="999" placeholder="Value" min="-2147483648" max="2147483647" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testIntegerUnsignedMinMaxSchema(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value UNSIGNED INTEGER NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="0" max="4294967295" step="1" />',
            $this->view->Form->input('value')
        );
    }
}
