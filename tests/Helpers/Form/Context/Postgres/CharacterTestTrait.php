<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Postgres;

use Fyre\DB\ConnectionManager;
use Fyre\ORM\ModelRegistry;
use Fyre\Validation\Rule;
use Fyre\Validation\Validator;

trait CharacterTestTrait
{
    public function testCharacterEntityValue(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                value CHARACTER(1) NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEntity([
            'value' => 'A',
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="text" value="A" placeholder="Value" maxlength="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testCharacterMaxLengthSchema(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                value CHARACTER(1) NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="text" placeholder="Value" maxlength="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testCharacterRequiredValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                value CHARACTER(1) NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $model = ModelRegistry::use('Contexts');

        $validator = new Validator();
        $validator->add('value', Rule::required());

        $model->setValidator($validator);

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="text" placeholder="Value" required maxlength="1" />',
            $this->view->Form->input('value')
        );
    }

    public function testCharacterSchemaDefaultValue(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                value CHARACTER(1) NOT NULL DEFAULT 'A',
                PRIMARY KEY (id)
            )
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="text" value="A" placeholder="Value" maxlength="1" />',
            $this->view->Form->input('value')
        );
    }
}
