<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context;

use
    Fyre\DateTime\DateTime,
    Fyre\DB\ConnectionManager,
    Fyre\ORM\ModelRegistry,
    Fyre\Validation\Rule,
    Fyre\Validation\Validator;

trait TimeTest
{

    public function testTimeSchema(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` TIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="time" placeholder="Value" />',
            $this->view->Form->input('value')
        );
    }

    public function testTimeRequiredValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` TIME NULL DEFAULT NULL,
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
            '<input id="value" name="value" type="time" placeholder="Value" required />',
            $this->view->Form->input('value')
        );
    }

    public function testTimeEntityValue(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` TIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEntity([
            'value' => DateTime::fromArray([2022, 1, 1, 12, 30])
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="time" value="12:30" placeholder="Value" />',
            $this->view->Form->input('value')
        );
    }

    public function testTimeSchemaDefaultValue(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` TIME NOT NULL DEFAULT '12:30:00',
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="time" value="12:30" placeholder="Value" />',
            $this->view->Form->input('value')
        );
    }

}
