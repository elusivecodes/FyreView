<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Postgres;

use Fyre\DateTime\DateTime;
use Fyre\DB\ConnectionManager;
use Fyre\ORM\ModelRegistry;
use Fyre\Validation\Rule;
use Fyre\Validation\Validator;

trait DateTestTrait
{
    public function testDateEntityValue(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                value DATE NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEntity([
            'value' => DateTime::fromArray([2022, 1, 1]),
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="date" value="2022-01-01" />',
            $this->view->Form->input('value')
        );
    }

    public function testDateRequiredValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                value DATE NULL DEFAULT NULL,
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
            '<input id="value" name="value" type="date" required />',
            $this->view->Form->input('value')
        );
    }

    public function testDateSchema(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                value DATE NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="date" />',
            $this->view->Form->input('value')
        );
    }

    public function testDateSchemaDefaultValue(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER GENERATED BY DEFAULT AS IDENTITY,
                value DATE NOT NULL DEFAULT '2022-01-01',
                PRIMARY KEY (id)
            )
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="date" value="2022-01-01" />',
            $this->view->Form->input('value')
        );
    }
}
