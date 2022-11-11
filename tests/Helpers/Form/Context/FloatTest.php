<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context;

use
    Fyre\DB\ConnectionManager,
    Fyre\ORM\ModelRegistry,
    Fyre\Validation\Rule,
    Fyre\Validation\Validator;

trait FloatTest
{

    public function testFloatMinMaxSchema(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` FLOAT NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="-999999999999" max="999999999999" step="any" />',
            $this->view->Form->input('value')
        );
    }

    public function testFloatUnsignedMinMaxSchema(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` FLOAT UNSIGNED NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="0" max="999999999999" step="any" />',
            $this->view->Form->input('value')
        );
    }

    public function testFloatGreaterThanValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` FLOAT NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $validator = new Validator();
        $validator->add('value', Rule::greaterThan(100));

        $model->setValidator($validator);

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="101" max="999999999999" step="any" />',
            $this->view->Form->input('value')
        );
    }

    public function testFloatGreaterThanOrEqualsValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` FLOAT NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $validator = new Validator();
        $validator->add('value', Rule::greaterThanOrEquals(100));

        $model->setValidator($validator);

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="100" max="999999999999" step="any" />',
            $this->view->Form->input('value')
        );
    }

    public function testFloatLessThanValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` FLOAT NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $validator = new Validator();
        $validator->add('value', Rule::lessThan(1000));

        $model->setValidator($validator);

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="-999999999999" max="999" step="any" />',
            $this->view->Form->input('value')
        );
    }

    public function testFloatLessThanOrEqualsValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` FLOAT NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $validator = new Validator();
        $validator->add('value', Rule::lessThanOrEquals(1000));

        $model->setValidator($validator);

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="-999999999999" max="1000" step="any" />',
            $this->view->Form->input('value')
        );
    }

    public function testFloatBetweenValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` FLOAT NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $validator = new Validator();
        $validator->add('value', Rule::between(100, 1000));

        $model->setValidator($validator);

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="100" max="1000" step="any" />',
            $this->view->Form->input('value')
        );
    }

    public function testFloatRequiredValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` FLOAT NULL DEFAULT NULL,
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
            '<input id="value" name="value" type="number" placeholder="Value" min="-999999999999" max="999999999999" step="any" required />',
            $this->view->Form->input('value')
        );
    }

    public function testFloatEntityValue(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` FLOAT NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEntity([
            'value' => 100.123
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" value="100.123" placeholder="Value" min="-999999999999" max="999999999999" step="any" />',
            $this->view->Form->input('value')
        );
    }

    public function testFloatSchemaDefaultValue(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` FLOAT NOT NULL DEFAULT 100.123,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" value="100.123" placeholder="Value" min="-999999999999" max="999999999999" step="any" />',
            $this->view->Form->input('value')
        );
    }

}
