<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Mysql;

use Fyre\Validation\Rule;

trait SmallIntTestTrait
{
    public function testSmallIntBetweenValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value SMALLINT NULL DEFAULT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->validator->add('value', Rule::between(100, 1000));

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="100" max="1000" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testSmallIntEntityValue(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value SMALLINT NULL DEFAULT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $entity = $this->model->newEntity([
            'value' => 999,
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" value="999" placeholder="Value" min="-32768" max="32767" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testSmallIntGreaterThanOrEqualsValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value SMALLINT NULL DEFAULT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->validator->add('value', Rule::greaterThanOrEquals(100));

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="100" max="32767" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testSmallIntGreaterThanValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value SMALLINT NULL DEFAULT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->validator->add('value', Rule::greaterThan(100));

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="101" max="32767" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testSmallIntLessThanOrEqualsValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value SMALLINT NULL DEFAULT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->validator->add('value', Rule::lessThanOrEquals(1000));

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="-32768" max="1000" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testSmallIntLessThanValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value SMALLINT NULL DEFAULT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->validator->add('value', Rule::lessThan(1000));

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="-32768" max="999" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testSmallIntMinMaxSchema(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value SMALLINT NULL DEFAULT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="-32768" max="32767" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testSmallIntRequiredValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value SMALLINT NULL DEFAULT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->validator->add('value', Rule::required());

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="-32768" max="32767" step="1" required />',
            $this->view->Form->input('value')
        );
    }

    public function testSmallIntSchemaDefaultValue(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value SMALLINT NOT NULL DEFAULT 999,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" value="999" placeholder="Value" min="-32768" max="32767" step="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testSmallIntUnsignedMinMaxSchema(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value SMALLINT UNSIGNED NULL DEFAULT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="0" max="65535" step="1" />',
            $this->view->Form->input('value')
        );
    }
}
