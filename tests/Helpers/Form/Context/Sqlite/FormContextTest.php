<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Sqlite;

use Fyre\DB\ConnectionManager;
use Fyre\DB\Handlers\Sqlite\SqliteConnection;
use Fyre\ORM\ModelRegistry;
use Fyre\Security\CsrfProtection;
use Fyre\Server\ServerRequest;
use Fyre\Utility\HtmlHelper;
use Fyre\View\View;
use PHPUnit\Framework\TestCase;

final class FormContextTest extends TestCase
{
    use BigIntTestTrait;
    use BlobTestTrait;
    use BooleanTestTrait;
    use DateTestTrait;
    use DateTimeTestTrait;
    use DoubleTestTrait;
    use IntegerTestTrait;
    use NumericTestTrait;
    use RealTestTrait;
    use SmallIntTestTrait;
    use TextTestTrait;
    use TimeTestTrait;

    protected View $view;

    protected function setUp(): void
    {
        ConnectionManager::clear();
        ConnectionManager::setConfig('default', [
            'className' => SqliteConnection::class,
            'persist' => true,
        ]);

        $connection = ConnectionManager::use();

        $connection->query('DROP TABLE IF EXISTS contexts');
        $connection->query('DROP TABLE IF EXISTS parents');
        $connection->query('DROP TABLE IF EXISTS children');
        $connection->query('DROP TABLE IF EXISTS contexts_children');

        ModelRegistry::clear();

        CsrfProtection::disable();
        HtmlHelper::setCharset('UTF-8');

        $request = new ServerRequest([
            'globals' => [
                'server' => [
                    'REQUEST_URI' => '/test',
                ],
            ],
        ]);

        $this->view = new View($request);
    }

    protected function tearDown(): void
    {
        $connection = ConnectionManager::use();

        $connection->query('DROP TABLE IF EXISTS contexts');
        $connection->query('DROP TABLE IF EXISTS parents');
        $connection->query('DROP TABLE IF EXISTS children');
        $connection->query('DROP TABLE IF EXISTS contexts_children');
    }
}
