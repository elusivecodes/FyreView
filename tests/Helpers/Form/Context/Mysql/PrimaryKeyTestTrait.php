<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Mysql;

trait PrimaryKeyTestTrait
{
    public function testPrimaryKey(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
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
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
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
