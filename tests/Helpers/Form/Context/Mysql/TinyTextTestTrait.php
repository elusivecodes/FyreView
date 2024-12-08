<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Mysql;

use Fyre\Validation\Rule;

trait TinyTextTestTrait
{
    public function testTinyTextEntityValue(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value TINYTEXT NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $entity = $this->model->newEntity([
            'value' => 'Test',
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<textarea id="value" name="value" placeholder="Value" maxlength="255">Test</textarea>',
            $this->view->Form->input('value')
        );
    }

    public function testTinyTextMaxLengthSchema(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value TINYTEXT NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<textarea id="value" name="value" placeholder="Value" maxlength="255"></textarea>',
            $this->view->Form->input('value')
        );
    }

    public function testTinyTextMaxLengthValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value TINYTEXT NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->validator->add('value', Rule::maxLength(100));

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<textarea id="value" name="value" placeholder="Value" maxlength="100"></textarea>',
            $this->view->Form->input('value')
        );
    }

    public function testTinyTextRequiredValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value TINYTEXT NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->validator->add('value', Rule::required());

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<textarea id="value" name="value" placeholder="Value" required maxlength="255"></textarea>',
            $this->view->Form->input('value')
        );
    }

    public function testTinyTextSchemaDefaultValue(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value TINYTEXT NOT NULL DEFAULT 'Test' COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<textarea id="value" name="value" placeholder="Value" maxlength="255">Test</textarea>',
            $this->view->Form->input('value')
        );
    }
}
