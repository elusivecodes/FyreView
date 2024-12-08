<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Postgres;

trait RelationshipTestTrait
{
    public function testBelongsTo(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                parent_id INTEGER NOT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $this->db->query(<<<'EOT'
            CREATE TABLE parents (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                value TEXT NOT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $this->model->belongsTo('Parents');

        $entity = $this->model->newEntity([
            'parent' => [
                'value' => 'Test',
            ],
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<textarea id="parent-value" name="parent[value]" placeholder="Value">Test</textarea>',
            $this->view->Form->input('parent.value')
        );
    }

    public function testBelongsToForeignKey(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                parent_id INTEGER NOT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $this->db->query(<<<'EOT'
            CREATE TABLE parents (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                value TEXT NOT NULL,
                PRIMARY KEY (id)
            )
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
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                PRIMARY KEY (id)
            )
        EOT);

        $this->db->query(<<<'EOT'
            CREATE TABLE children (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                context_id INTEGER NOT NULL,
                value TEXT NOT NULL,
                PRIMARY KEY (id)
            )
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
            '<textarea id="children-0-value" name="children[0][value]" placeholder="Value">Test</textarea>',
            $this->view->Form->input('children.0.value')
        );
    }

    public function testHasOne(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                PRIMARY KEY (id)
            )
        EOT);

        $this->db->query(<<<'EOT'
            CREATE TABLE children (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                context_id INTEGER NOT NULL,
                value TEXT NOT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $this->model->hasOne('Children');

        $entity = $this->model->newEntity([
            'child' => [
                'value' => 'Test',
            ],
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<textarea id="child-value" name="child[value]" placeholder="Value">Test</textarea>',
            $this->view->Form->input('child.value')
        );
    }

    public function testManyToMany(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                PRIMARY KEY (id)
            )
        EOT);

        $this->db->query(<<<'EOT'
            CREATE TABLE contexts_children (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                context_id INTEGER NOT NULL,
                child_id INTEGER NOT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $this->db->query(<<<'EOT'
            CREATE TABLE children (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                value TEXT NOT NULL,
                PRIMARY KEY (id)
            )
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
            '<textarea id="children-0-value" name="children[0][value]" placeholder="Value">Test</textarea>',
            $this->view->Form->input('children.0.value')
        );
    }
}
