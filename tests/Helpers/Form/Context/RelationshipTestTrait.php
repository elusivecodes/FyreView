<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context;

use Fyre\DB\ConnectionManager;
use Fyre\ORM\ModelRegistry;

trait RelationshipTestTrait
{

    public function testBelongsTo(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `parent_id` INT(10) UNSIGNED NOT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $connection->query(<<<EOT
            CREATE TABLE `parents` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');
        $model->belongsTo('Parents');

        $entity = $model->newEntity([
            'parent' => [
                'value' => 'Test'
            ]
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<textarea id="parent-value" name="parent[value]" placeholder="Value" maxlength="65535">Test</textarea>',
            $this->view->Form->input('parent.value')
        );
    }

    public function testHasOne(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $connection->query(<<<EOT
            CREATE TABLE `children` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `context_id` INT(10) UNSIGNED NOT NULL,
                `value` TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');
        $model->hasOne('Children');

        $entity = $model->newEntity([
            'child' => [
                'value' => 'Test'
            ]
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<textarea id="child-value" name="child[value]" placeholder="Value" maxlength="65535">Test</textarea>',
            $this->view->Form->input('child.value')
        );
    }

    public function testHasMany(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $connection->query(<<<EOT
            CREATE TABLE `children` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `context_id` INT(10) UNSIGNED NOT NULL,
                `value` TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');
        $model->hasMany('Children');

        $entity = $model->newEntity([
            'children' => [
                [
                    'value' => 'Test'
                ]
            ]
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<textarea id="children-0-value" name="children[0][value]" placeholder="Value" maxlength="65535">Test</textarea>',
            $this->view->Form->input('children.0.value')
        );
    }

    public function testManyToMany(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $connection->query(<<<EOT
            CREATE TABLE `contexts_children` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `context_id` INT(10) UNSIGNED NOT NULL,
                `child_id` INT(10) UNSIGNED NOT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $connection->query(<<<EOT
            CREATE TABLE `children` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');
        $model->manyToMany('Children');

        $entity = $model->newEntity([
            'children' => [
                [
                    'value' => 'Test'
                ]
            ]
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<textarea id="children-0-value" name="children[0][value]" placeholder="Value" maxlength="65535">Test</textarea>',
            $this->view->Form->input('children.0.value')
        );
    }

    public function testBelongsToForeignKey(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<EOT
            CREATE TABLE `contexts` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `parent_id` INT(10) UNSIGNED NOT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $connection->query(<<<EOT
            CREATE TABLE `parents` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `value` TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (`id`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB
        EOT);

        $model = ModelRegistry::use('Contexts');
        $model->belongsTo('Parents');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<select id="parent-id" name="parent_id"></select>',
            $this->view->Form->input('parent_id')
        );
    }

}
