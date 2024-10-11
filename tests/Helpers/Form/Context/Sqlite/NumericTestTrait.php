<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Sqlite;

use Fyre\DB\ConnectionManager;
use Fyre\ORM\ModelRegistry;
use Fyre\Validation\Rule;
use Fyre\Validation\Validator;

trait NumericTestTrait
{
    public function testNumericBetweenValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value NUMERIC(10,2) NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $model = ModelRegistry::use('Contexts');

        $validator = new Validator();
        $validator->add('value', Rule::between(100, 1000));

        $model->setValidator($validator);

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="100" max="1000" step="0.01" />',
            $this->view->Form->input('value')
        );
    }

    public function testNumericEntityValue(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value NUMERIC(10,2) NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEntity([
            'value' => 100.99,
        ]);

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" value="100.99" placeholder="Value" min="-9999999999" max="9999999999" step="0.01" />',
            $this->view->Form->input('value')
        );
    }

    public function testNumericGreaterThanOrEqualsValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value NUMERIC(10,2) NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $model = ModelRegistry::use('Contexts');

        $validator = new Validator();
        $validator->add('value', Rule::greaterThanOrEquals(100));

        $model->setValidator($validator);

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="100" max="9999999999" step="0.01" />',
            $this->view->Form->input('value')
        );
    }

    public function testNumericGreaterThanValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value NUMERIC(10,2) NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $model = ModelRegistry::use('Contexts');

        $validator = new Validator();
        $validator->add('value', Rule::greaterThan(100));

        $model->setValidator($validator);

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="101" max="9999999999" step="0.01" />',
            $this->view->Form->input('value')
        );
    }

    public function testNumericLessThanOrEqualsValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value NUMERIC(10,2) NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $model = ModelRegistry::use('Contexts');

        $validator = new Validator();
        $validator->add('value', Rule::lessThanOrEquals(1000));

        $model->setValidator($validator);

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="-9999999999" max="1000" step="0.01" />',
            $this->view->Form->input('value')
        );
    }

    public function testNumericLessThanValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value NUMERIC(10,2) NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $model = ModelRegistry::use('Contexts');

        $validator = new Validator();
        $validator->add('value', Rule::lessThan(1000));

        $model->setValidator($validator);

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="-9999999999" max="999" step="0.01" />',
            $this->view->Form->input('value')
        );
    }

    public function testNumericMinMaxSchema(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value NUMERIC(10,2) NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="-9999999999" max="9999999999" step="0.01" />',
            $this->view->Form->input('value')
        );
    }

    public function testNumericRequiredValidation(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value NUMERIC(10,2) NULL DEFAULT NULL,
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
            '<input id="value" name="value" type="number" placeholder="Value" min="-9999999999" max="9999999999" step="0.01" required />',
            $this->view->Form->input('value')
        );
    }

    public function testNumericSchemaDefaultValue(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value NUMERIC(10,2) NOT NULL DEFAULT 100.99,
                PRIMARY KEY (id)
            )
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" value="100.99" placeholder="Value" min="-9999999999" max="9999999999" step="0.01" />',
            $this->view->Form->input('value')
        );
    }

    public function testNumericUnsignedMinMaxSchema(): void
    {
        $connection = ConnectionManager::use();

        $connection->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value UNSIGNED NUMERIC(10,2) NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $model = ModelRegistry::use('Contexts');

        $entity = $model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="number" placeholder="Value" min="0" max="9999999999" step="0.01" />',
            $this->view->Form->input('value')
        );
    }
}