<?php
declare(strict_types=1);

namespace Tests\Helpers\Form;

use Fyre\Security\CsrfProtection;
use Fyre\Server\ServerRequest;
use Fyre\Utility\HtmlHelper;
use Fyre\View\Exceptions\FormException;
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
        $_POST = [];
    }
}
