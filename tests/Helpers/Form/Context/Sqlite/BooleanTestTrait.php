<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Sqlite;

use Fyre\Validation\Rule;

trait BooleanTestTrait
{
    public function testBooleanEntityValue(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTNEGER NOT NULL,
                value BOOLEAN NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $entity = $this->model->newEntity([
            'value' => true,
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input name="value" type="hidden" value="0" /><input id="value" name="value" type="checkbox" value="1" checked />',
            $this->view->Form->input('value')
        );
    }

    public function testBooleanRequiredValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTNEGER NOT NULL,
                value BOOLEAN NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $this->validator->add('value', Rule::required());

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input name="value" type="hidden" value="0" /><input id="value" name="value" type="checkbox" value="1" required />',
            $this->view->Form->input('value')
        );
    }

    public function testBooleanSchema(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTNEGER NOT NULL,
                value BOOLEAN NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input name="value" type="hidden" value="0" /><input id="value" name="value" type="checkbox" value="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testBooleanSchemaDefaultValue(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTNEGER NOT NULL,
                value BOOLEAN NOT NULL DEFAULT 1,
                PRIMARY KEY (id)
            )
        EOT);

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input name="value" type="hidden" value="0" /><input id="value" name="value" type="checkbox" value="1" checked />',
            $this->view->Form->input('value')
        );
    }
}
