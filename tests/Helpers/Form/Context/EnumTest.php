<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context;

use
    Fyre\DB\ConnectionManager,
    Fyre\ORM\ModelRegistry,
    Fyre\Validation\Rule,
    Fyre\Validation\Validator;

trait EnumTest
{

    public function testEnumSchema(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` ENUM('A','B','C') NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<select id="value" name="value"></select>',
            $this->view->Form->input('value')
        );
    }

    public function testEnumRequiredValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` ENUM('A','B','C') NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
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
            '<select id="value" name="value" required></select>',
            $this->view->Form->input('value')
        );
    }

    public function testEnumEntityValue(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` ENUM('A','B','C') NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEntity([
            'value' => 'B'
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<select id="value" name="value"><option value="B" selected></option></select>',
            $this->view->Form->input('value')
        );
    }

    public function testEnumSchemaDefaultValue(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` ENUM('A','B','C') NOT NULL DEFAULT 'B' COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<select id="value" name="value"><option value="B" selected></option></select>',
            $this->view->Form->input('value')
        );
    }

}
