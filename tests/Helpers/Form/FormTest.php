<?php
declare(strict_types=1);

namespace Tests\Helpers\Form;

use Fyre\Config\Config;
use Fyre\Container\Container;
use Fyre\Form\FormBuilder;
use Fyre\Security\CsrfProtection;
use Fyre\Server\ServerRequest;
use Fyre\Utility\HtmlHelper;
use Fyre\View\CellRegistry;
use Fyre\View\Exceptions\FormException;
use Fyre\View\HelperRegistry;
use Fyre\View\TemplateLocator;
use Fyre\View\View;
use PHPUnit\Framework\TestCase;
use stdClass;

final class FormTest extends TestCase
{
    use ButtonTestTrait;
    use CheckboxTestTrait;
    use CloseTestTrait;
    use DateTestTrait;
    use DateTimeTestTrait;
    use FieldsetCloseTestTrait;
    use FieldsetOpenTestTrait;
    use InputTestTrait;
    use InputTypeTestTrait;
    use LabelTestTrait;
    use LegendTestTrait;
    use NumberTestTrait;
    use OpenMultipartTestTrait;
    use OpenTestTrait;
    use RadioTestTrait;
    use SelectMultiTestTrait;
    use SelectTestTrait;
    use TextareaTestTrait;
    use TimeTestTrait;

    protected Container $container;

    protected View $view;

    public function testInvalidContext(): void
    {
        $this->expectException(FormException::class);

        $this->view->Form->open(new stdClass());
    }

    public function testInvalidInputType(): void
    {
        $this->expectException(FormException::class);

        $this->view->Form->input('input', [
            'type' => 'invalid',
        ]);
    }

    public function testUnclosedForm(): void
    {
        $this->expectException(FormException::class);

        $this->view->Form->open();
        $this->view->Form->open();
    }

    protected function setUp(): void
    {
        $this->container = new Container();
        $this->container->singleton(Config::class);
        $this->container->singleton(TemplateLocator::class);
        $this->container->singleton(HelperRegistry::class);
        $this->container->singleton(CellRegistry::class);
        $this->container->singleton(HtmlHelper::class);
        $this->container->singleton(FormBuilder::class);
        $this->container->singleton(CsrfProtection::class);

        $this->container->use(Config::class)->set('Csrf.salt', 'l2wyQow3eTwQeTWcfZnlgU8FnbiWljpGjQvNP2pL');

        $request = $this->container->build(ServerRequest::class, [
            'options' => [
                'globals' => [
                    'server' => [
                        'REQUEST_URI' => '/test',
                    ],
                ],
            ],
        ]);

        $this->view = $this->container->build(View::class, ['request' => $request]);
    }

    protected function tearDown(): void
    {
        $_POST = [];
    }
}
