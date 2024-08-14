<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Mysql;

use Fyre\DB\ConnectionManager;
use Fyre\ORM\ModelRegistry;

trait PrimaryKeyTestTrait
{
    public function testPrimaryKey(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="id" name="id" type="hidden" />',
            $this->view->Form->input('id')
        );
    }

    public function testPrimaryKeyValue(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEntity([
            'id' => 1,
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="id" name="id" type="hidden" value="1" />',
            $this->view->Form->input('id')
        );
    }
}
