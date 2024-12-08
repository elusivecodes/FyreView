<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Mysql;

use Fyre\Validation\Rule;

trait SetTestTrait
{
    public function testSetEntityValue(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value SET('A','B','C') NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $entity = $this->model->newEntity([
            'value' => 'B',
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input name="value" type="hidden" value="" /><select id="value" name="value[]" multiple><option value="A">A</option><option value="B" selected>B</option><option value="C">C</option></select>',
            $this->view->Form->input('value')
        );
    }

    public function testSetRequiredValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value SET('A','B','C') NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->validator->add('value', Rule::required());

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input name="value" type="hidden" value="" /><select id="value" name="value[]" multiple required><option value="A">A</option><option value="B">B</option><option value="C">C</option></select>',
            $this->view->Form->input('value')
        );
    }

    public function testSetSchema(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value SET('A','B','C') NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input name="value" type="hidden" value="" /><select id="value" name="value[]" multiple><option value="A">A</option><option value="B">B</option><option value="C">C</option></select>',
            $this->view->Form->input('value')
        );
    }

    public function testSetSchemaDefaultValue(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value SET('A','B','C') NOT NULL DEFAULT 'B' COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input name="value" type="hidden" value="" /><select id="value" name="value[]" multiple><option value="A">A</option><option value="B" selected>B</option><option value="C">C</option></select>',
            $this->view->Form->input('value')
        );
    }
}
