<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Mysql;

use Fyre\DB\ConnectionManager;
use Fyre\DB\Handlers\Mysql\MysqlConnection;
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
    use CharTestTrait;
    use DateTestTrait;
    use DatetimeTestTrait;
    use DecimalTestTrait;
    use DoubleTestTrait;
    use EnumTestTrait;
    use FloatTestTrait;
    use IntTestTrait;
    use LongBlobTestTrait;
    use LongTextTestTrait;
    use MediumBlobTestTrait;
    use MediumIntTestTrait;
    use MediumTextTestTrait;
    use PrimaryKeyTestTrait;
    use RelationshipTestTrait;
    use SetTestTrait;
    use SmallIntTestTrait;
    use TextTestTrait;
    use TimeTestTrait;
    use TinyBlobTestTrait;
    use TinyIntTestTrait;
    use TinyTextTestTrait;
    use VarcharTestTrait;

    protected View $view;

    protected function setUp(): void
    {
        ConnectionManager::clear();
        ConnectionManager::setConfig('default', [
            'className' => MysqlConnection::class,
            'host' => getenv('MYSQL_HOST'),
            'username' => getenv('MYSQL_USERNAME'),
            'password' => getenv('MYSQL_PASSWORD'),
            'database' => getenv('MYSQL_DATABASE'),
            'port' => getenv('MYSQL_PORT'),
            'collation' => 'utf8mb4_unicode_ci',
            'charset' => 'utf8mb4',
            'compress' => true,
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
