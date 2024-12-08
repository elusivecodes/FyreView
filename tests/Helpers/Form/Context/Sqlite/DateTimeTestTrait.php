<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Sqlite;

use Fyre\DateTime\DateTime;
use Fyre\Validation\Rule;

trait DatetimeTestTrait
{
    public function testDatetimeEntityValue(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $entity = $this->model->newEntity([
            'value' => DateTime::fromArray([2022, 1, 1]),
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="datetime-local" value="2022-01-01T00:00" />',
            $this->view->Form->input('value')
        );
    }

    public function testDatetimeRequiredValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $this->validator->add('value', Rule::required());

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="datetime-local" required />',
            $this->view->Form->input('value')
        );
    }

    public function testDatetimeSchema(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="datetime-local" />',
            $this->view->Form->input('value')
        );
    }

    public function testDatetimeSchemaDefaultValue(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value DATETIME NOT NULL DEFAULT '2022-01-01 00:00:00',
                PRIMARY KEY (id)
            )
        EOT);

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="datetime-local" value="2022-01-01T00:00" />',
            $this->view->Form->input('value')
        );
    }
}
