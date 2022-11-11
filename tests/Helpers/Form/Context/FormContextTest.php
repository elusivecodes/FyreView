<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context;

use
    Fyre\CSRF\CsrfProtection,
    Fyre\DB\ConnectionManager,
    Fyre\DB\Handlers\MySQL\MySQLConnection,
    Fyre\HTMLHelper\HtmlHelper,
    Fyre\ORM\ModelRegistry,
    Fyre\Server\ServerRequest,
    Fyre\Server\ClientResponse,
    Fyre\View\View,
    PHPUnit\Framework\TestCase,
    Tests\Mock\TestController;

final class FormContextTest extends TestCase
{

    protected View $view;

    use
        BigIntTest,
        BlobTest,
        BooleanTest,
        CharTest,
        DateTest,
        DatetimeTest,
        DecimalTest,
        DoubleTest,
        EnumTest,
        FloatTest,
        IntTest,
        LongBlobTest,
        LongTextTest,
        MediumBlobTest,
        MediumIntTest,
        MediumTextTest,
        RelationshipTest,
        SetTest,
        SmallIntTest,
        TextTest,
        TimeTest,
        TinyBlobTest,
        TinyIntTest,
        TinyTextTest,
        VarcharTest;

    protected function setUp(): void
    {
        ConnectionManager::clear();
        ConnectionManager::setConfig('default', [
            'className' => MySQLConnection::class,
            'host' => getenv('DB_HOST'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'database' => getenv('DB_NAME'),
            'port' => getenv('DB_PORT'),
            'collation' => 'utf8mb4_unicode_ci',
            'charset' => 'utf8mb4',
            'compress' => true,
            'persist' => true
        ]);

        ModelRegistry::clear();

        CsrfProtection::disable();
        HtmlHelper::setCharset('UTF-8');

        $request = new ServerRequest();
        $response = new ClientResponse();
        $controller = new TestController($request, $response);

        $request->getUri()->setPath('/test');

        $this->view = new View($controller);
    }

    protected function tearDown(): void
    {
        $connection = ConnectionManager::use();

        $connection->query('DROP TABLE IF EXISTS `contexts`');
        $connection->query('DROP TABLE IF EXISTS `parents`');
        $connection->query('DROP TABLE IF EXISTS `children`');
        $connection->query('DROP TABLE IF EXISTS `contexts_children`');
    }

}
