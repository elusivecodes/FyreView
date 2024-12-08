<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Sqlite;

use Fyre\DateTime\DateTime;
use Fyre\Validation\Rule;

trait DateTestTrait
{
    public function testDateEntityValue(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value DATE NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $entity = $this->model->newEntity([
            'value' => DateTime::fromArray([2022, 1, 1]),
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="date" value="2022-01-01" />',
            $this->view->Form->input('value')
        );
    }

    public function testDateRequiredValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value DATE NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $this->validator->add('value', Rule::required());

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="date" required />',
            $this->view->Form->input('value')
        );
    }

    public function testDateSchema(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value DATE NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="date" />',
            $this->view->Form->input('value')
        );
    }

    public function testDateSchemaDefaultValue(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value DATE NOT NULL DEFAULT '2022-01-01',
                PRIMARY KEY (id)
            )
        EOT);

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="date" value="2022-01-01" />',
            $this->view->Form->input('value')
        );
    }
}
