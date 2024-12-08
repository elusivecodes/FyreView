<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Mysql;

trait RelationshipTestTrait
{
    public function testBelongsTo(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                parent_id INT(10) UNSIGNED NOT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->db->query(<<<'EOT'
            CREATE TABLE parents (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->model->belongsTo('Parents');

        $entity = $this->model->newEntity([
            'parent' => [
                'value' => 'Test',
            ],
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<textarea id="parent-value" name="parent[value]" placeholder="Value" maxlength="65535">Test</textarea>',
            $this->view->Form->input('parent.value')
        );
    }

    public function testBelongsToForeignKey(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                parent_id INT(10) UNSIGNED NOT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->db->query(<<<'EOT'
            CREATE TABLE parents (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->model->belongsTo('Parents');

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<select id="parent-id" name="parent_id"></select>',
            $this->view->Form->input('parent_id')
        );
    }

    public function testHasMany(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->db->query(<<<'EOT'
            CREATE TABLE children (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                context_id INT(10) UNSIGNED NOT NULL,
                value TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->model->hasMany('Children');

        $entity = $this->model->newEntity([
            'children' => [
                [
                    'value' => 'Test',
                ],
            ],
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<textarea id="children-0-value" name="children[0][value]" placeholder="Value" maxlength="65535">Test</textarea>',
            $this->view->Form->input('children.0.value')
        );
    }

    public function testHasOne(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->db->query(<<<'EOT'
            CREATE TABLE children (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                context_id INT(10) UNSIGNED NOT NULL,
                value TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->model->hasOne('Children');

        $entity = $this->model->newEntity([
            'child' => [
                'value' => 'Test',
            ],
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<textarea id="child-value" name="child[value]" placeholder="Value" maxlength="65535">Test</textarea>',
            $this->view->Form->input('child.value')
        );
    }

    public function testManyToMany(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->db->query(<<<'EOT'
            CREATE TABLE contexts_children (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                context_id INT(10) UNSIGNED NOT NULL,
                child_id INT(10) UNSIGNED NOT NULL,
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->db->query(<<<'EOT'
            CREATE TABLE children (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                value TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (id)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $this->model->manyToMany('Children');

        $entity = $this->model->newEntity([
            'children' => [
                [
                    'value' => 'Test',
                ],
            ],
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<textarea id="children-0-value" name="children[0][value]" placeholder="Value" maxlength="65535">Test</textarea>',
            $this->view->Form->input('children.0.value')
        );
    }
}
