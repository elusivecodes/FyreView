<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context;

use
    Fyre\DateTime\DateTime,
    Fyre\DB\ConnectionManager,
    Fyre\ORM\ModelRegistry,
    Fyre\Validation\Rule,
    Fyre\Validation\Validator;

trait DatetimeTest
{

    public function testDatetimeSchema(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="datetime-local" placeholder="Value" />',
            $this->view->Form->input('value')
        );
    }

    public function testDatetimeRequiredValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $validator = new Validator();
        $validator->add('value', Rule::required());

        $model->setValidator($validator);

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="datetime-local" placeholder="Value" required />',
            $this->view->Form->input('value')
        );
    }

    public function testDatetimeEntityValue(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEntity([
            'value' => DateTime::fromArray([2022, 1, 1])
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="datetime-local" value="2022-01-01T00:00" placeholder="Value" />',
            $this->view->Form->input('value')
        );
    }

    public function testDatetimeSchemaDefaultValue(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` DATETIME NOT NULL DEFAULT '2022-01-01 00:00:00',
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="datetime-local" value="2022-01-01T00:00" placeholder="Value" />',
            $this->view->Form->input('value')
        );
    }

}
