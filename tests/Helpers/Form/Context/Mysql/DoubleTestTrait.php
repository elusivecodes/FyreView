<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Mysql;

use Fyre\Validation\Rule;

trait DoubleTestTrait
{
    public function testDoubleBetweenValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value DOUBLE NULL DEFAULT NULL,
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

    public function testDoubleEntityValue(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value DOUBLE NULL DEFAULT NULL,
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

    public function testDoubleGreaterThanOrEqualsValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value DOUBLE NULL DEFAULT NULL,
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

    public function testDoubleGreaterThanValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value DOUBLE NULL DEFAULT NULL,
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

    public function testDoubleLessThanOrEqualsValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value DOUBLE NULL DEFAULT NULL,
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

    public function testDoubleLessThanValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value DOUBLE NULL DEFAULT NULL,
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

    public function testDoubleMinMaxSchema(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value DOUBLE NULL DEFAULT NULL,
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

    public function testDoubleRequiredValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value DOUBLE NULL DEFAULT NULL,
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

    public function testDoubleSchemaDefaultValue(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value DOUBLE NOT NULL DEFAULT 100.123,
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

    public function testDoubleUnsignedMinMaxSchema(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value DOUBLE UNSIGNED NULL DEFAULT NULL,
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
