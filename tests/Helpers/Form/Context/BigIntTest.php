<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context;

use
    Fyre\DB\ConnectionManager,
    Fyre\ORM\ModelRegistry,
    Fyre\Validation\Rule,
    Fyre\Validation\Validator;

trait BigIntTest
{

    public function testBigIntMinMaxSchema(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` BIGINT NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testBigIntUnsignedMinMaxSchema(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` BIGINT UNSIGNED NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="0" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testBigIntGreaterThanValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` BIGINT NULL DEFAULT NULL,
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
            '<input id="value" name="value" type="number" placeholder="Value" min="101" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testBigIntGreaterThanOrEqualsValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` BIGINT NULL DEFAULT NULL,
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
            '<input id="value" name="value" type="number" placeholder="Value" min="100" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testBigIntLessThanValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` BIGINT NULL DEFAULT NULL,
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
            '<input id="value" name="value" type="number" placeholder="Value" max="999" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testBigIntLessThanOrEqualsValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` BIGINT NULL DEFAULT NULL,
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
            '<input id="value" name="value" type="number" placeholder="Value" max="1000" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testBigIntBetweenValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` BIGINT NULL DEFAULT NULL,
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
            '<input id="value" name="value" type="number" placeholder="Value" min="100" max="1000" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testBigIntRequiredValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` BIGINT NULL DEFAULT NULL,
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
            '<input id="value" name="value" type="number" placeholder="Value" step="1" required />',
            $this->view->Form->input('value')
        );
    }

    public function testBigIntEntityValue(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` BIGINT NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEntity([
            'value' => 999
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" value="999" placeholder="Value" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testBigIntSchemaDefaultValue(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` BIGINT NOT NULL DEFAULT 999,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" value="999" placeholder="Value" step="1" />',
            $this->view->Form->input('value')
        );
    }

}
