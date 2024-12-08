<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Sqlite;

use Fyre\Validation\Rule;

trait BlobTestTrait
{
    public function testBlobRequiredValidation(): void
    {
        $this->db->query(<<<'EOT'
            CREATE TABLE contexts (
                id INTEGER NOT NULL,
                value BLOB NULL DEFAULT NULL,
                PRIMARY KEY (id)
            )
        EOT);

        $this->validator->add('value', Rule::required());

        $entity = $this->model->newEmptyEntity();

        $this->view->Form->open($entity);

        $this->assertSame(
            '<input id="value" name="value" type="file" required />',
            $this->view->Form->input('value')
        );
    }
}
