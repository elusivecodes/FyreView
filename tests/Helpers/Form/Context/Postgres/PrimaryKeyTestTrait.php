<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Postgres;

trait PrimaryKeyTestTrait
{
    public function testPrimaryKey(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                PRIMARY KEY (id)
            )
        EOT);

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="id" name="id" type="hidden" />',
            $this->view->Form->input('id')
        );
    }

    public function testPrimaryKeyValue(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                PRIMARY KEY (id)
            )
        EOT);

        $entity = $this->model->newEntity([
            'id' => 1,
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="id" name="id" type="hidden" value="1" />',
            $this->view->Form->input('id')
        );
    }
}
