<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Mysql;

use Fyre\DB\ConnectionManager;
use Fyre\ORM\ModelRegistry;
use Fyre\Validation\Rule;
use Fyre\Validation\Validator;

trait EnumTestTrait
{
    public function testEnumEntityValue(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value ENUM('A','B','C') NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEntity([
            'value' => 'B',
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<select id="value" name="value"><option value="A">A</option><option value="B" selected>B</option><option value="C">C</option></select>',
            $this->view->Form->input('value')
        );
    }

    public function testEnumRequiredValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value ENUM('A','B','C') NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $validator = new Validator();
        $validator->add('value', Rule::required());

        $model->setValidator($validator);

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<select id="value" name="value" required><option value="A">A</option><option value="B">B</option><option value="C">C</option></select>',
            $this->view->Form->input('value')
        );
    }

    public function testEnumSchema(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value ENUM('A','B','C') NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<select id="value" name="value"><option value="A">A</option><option value="B">B</option><option value="C">C</option></select>',
            $this->view->Form->input('value')
        );
    }

    public function testEnumSchemaDefaultValue(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value ENUM('A','B','C') NOT NULL DEFAULT 'B' COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<select id="value" name="value"><option value="A">A</option><option value="B" selected>B</option><option value="C">C</option></select>',
            $this->view->Form->input('value')
        );
    }
}
