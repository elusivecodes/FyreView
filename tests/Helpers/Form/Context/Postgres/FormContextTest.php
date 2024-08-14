<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Postgres;

use Fyre\DB\ConnectionManager;
use Fyre\DB\Handlers\Postgres\PostgresConnection;
use Fyre\ORM\ModelRegistry;
use Fyre\Security\CsrfProtection;
use Fyre\Server\ServerRequest;
use Fyre\Utility\HtmlHelper;
use Fyre\View\View;
use PHPUnit\Framework\TestCase;

final class FormContextTest extends TestCase
{
    use BigIntTestTrait;
    use BooleanTestTrait;
    use ByteaTestTrait;
    use CharacterTestTrait;
    use CharacterVaryingTestTrait;
    use DateTestTrait;
    use DoublePrecisionTestTrait;
    use IntegerTestTrait;
    use NumericTestTrait;
    use PrimaryKeyTestTrait;
    use RealTestTrait;
    use RelationshipTestTrait;
    use SmallIntTestTrait;
    use TextTestTrait;
    use TimestampTestTrait;
    use TimeTestTrait;

    protected View $view;

    protected function setUp(): void
    {
        ConnectionManager::clear();
        ConnectionManager::setConfig('default', [
            'className' => PostgresConnection::class,
            'host' => getenv('POSTGRES_HOST'),
            'username' => getenv('POSTGRES_USERNAME'),
            'password' => getenv('POSTGRES_PASSWORD'),
            'database' => getenv('POSTGRES_DATABASE'),
            'port' => getenv('POSTGRES_PORT'),
            'charset' => 'utf8',
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
