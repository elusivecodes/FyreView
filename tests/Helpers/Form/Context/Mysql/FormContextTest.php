<?php
declare(strict_types=1);

namespace Tests\Helpers\Form\Context\Mysql;

use Fyre\Config\Config;
use Fyre\Container\Container;
use Fyre\DB\Connection;
use Fyre\DB\ConnectionManager;
use Fyre\DB\Handlers\Mysql\MysqlConnection;
use Fyre\DB\TypeParser;
use Fyre\Entity\EntityLocator;
use Fyre\Form\FormBuilder;
use Fyre\ORM\BehaviorRegistry;
use Fyre\ORM\Model;
use Fyre\ORM\ModelRegistry;
use Fyre\Schema\SchemaRegistry;
use Fyre\Server\ServerRequest;
use Fyre\Utility\HtmlHelper;
use Fyre\Utility\Inflector;
use Fyre\Validation\Validator;
use Fyre\View\CellRegistry;
use Fyre\View\HelperRegistry;
use Fyre\View\TemplateLocator;
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

    protected Connection $db;

    protected Model $model;

    protected Validator $validator;

    protected View $view;

    protected function setUp(): void
    {
        $container = new Container();
        $container->singleton(Config::class);
        $container->singleton(TemplateLocator::class);
        $container->singleton(HelperRegistry::class);
        $container->singleton(CellRegistry::class);
        $container->singleton(HtmlHelper::class);
        $container->singleton(FormBuilder::class);
        $container->singleton(ConnectionManager::class);
        $container->singleton(TypeParser::class);
        $container->singleton(Inflector::class);
        $container->singleton(ConnectionManager::class);
        $container->singleton(SchemaRegistry::class);
        $container->singleton(ModelRegistry::class);
        $container->singleton(BehaviorRegistry::class);
        $container->singleton(EntityLocator::class);

        $container->use(Config::class)
            ->set('Database', [
                'default' => [
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
                ],
            ]);

        $this->db = $container->use(ConnectionManager::class)->use();

        $this->db->query('DROP TABLE IF EXISTS contexts');
        $this->db->query('DROP TABLE IF EXISTS parents');
        $this->db->query('DROP TABLE IF EXISTS children');
        $this->db->query('DROP TABLE IF EXISTS contexts_children');

        $this->model = $container->use(ModelRegistry::class)->use('Contexts');
        $this->validator = $container->build(Validator::class);

        $this->model->setValidator($this->validator);

        $request = $container->build(ServerRequest::class, [
            'options' => [
                'globals' => [
                    'server' => [
                        'REQUEST_URI' => '/test',
                    ],
                ],
            ],
        ]);

        $this->view = $container->build(View::class, ['request' => $request]);
    }

    protected function tearDown(): void
    {
        $this->db->query('DROP TABLE IF EXISTS contexts');
        $this->db->query('DROP TABLE IF EXISTS parents');
        $this->db->query('DROP TABLE IF EXISTS children');
        $this->db->query('DROP TABLE IF EXISTS contexts_children');
    }
}
