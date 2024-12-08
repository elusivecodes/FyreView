<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Mysql;

use Fyre\Validation\Rule;

trait FloatTestTrait
{
    public function testFloatBetweenValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value FLOAT NULL DEFAULT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->validator->add('value', Rule::between(100, 1000));

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="100" max="1000" step="any" />',
            $this->view->Form->input('value')
        );
    }

    public function testFloatEntityValue(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value FLOAT NULL DEFAULT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $entity = $this->model->newEntity([
            'value' => 100.123,
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" value="100.123" placeholder="Value" step="any" />',
            $this->view->Form->input('value')
        );
    }

    public function testFloatGreaterThanOrEqualsValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value FLOAT NULL DEFAULT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->validator->add('value', Rule::greaterThanOrEquals(100));

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="100" step="any" />',
            $this->view->Form->input('value')
        );
    }

    public function testFloatGreaterThanValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value FLOAT NULL DEFAULT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->validator->add('value', Rule::greaterThan(100));

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="101" step="any" />',
            $this->view->Form->input('value')
        );
    }

    public function testFloatLessThanOrEqualsValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value FLOAT NULL DEFAULT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->validator->add('value', Rule::lessThanOrEquals(1000));

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" max="1000" step="any" />',
            $this->view->Form->input('value')
        );
    }

    public function testFloatLessThanValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value FLOAT NULL DEFAULT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->validator->add('value', Rule::lessThan(1000));

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" max="999" step="any" />',
            $this->view->Form->input('value')
        );
    }

    public function testFloatMinMaxSchema(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value FLOAT NULL DEFAULT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" step="any" />',
            $this->view->Form->input('value')
        );
    }

    public function testFloatRequiredValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value FLOAT NULL DEFAULT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->validator->add('value', Rule::required());

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" step="any" required />',
            $this->view->Form->input('value')
        );
    }

    public function testFloatSchemaDefaultValue(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value FLOAT NOT NULL DEFAULT 100.123,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" value="100.123" placeholder="Value" step="any" />',
            $this->view->Form->input('value')
        );
    }

    public function testFloatUnsignedMinMaxSchema(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value FLOAT UNSIGNED NULL DEFAULT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="0" step="any" />',
            $this->view->Form->input('value')
        );
    }
}
