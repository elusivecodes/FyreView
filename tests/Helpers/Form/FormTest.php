<?php
declare(strict_types=1);

namespace Tests\Helpers\Form;

use
    Fyre\CSRF\CsrfProtection,
    Fyre\HTMLHelper\HtmlHelper,
    Fyre\Server\ServerRequest,
    Fyre\Server\ClientResponse,
    Fyre\View\Exceptions\FormException,
    Fyre\View\View,
    PHPUnit\Framework\TestCase,
    stdClass,
    Tests\Mock\TestController;

final class FormTest extends TestCase
{

    protected View $view;

    use
        ButtonTest,
        CheckboxTest,
        CloseTest,
        DateTest,
        DateTimeTest,
        FieldsetCloseTest,
        FieldsetOpenTest,
        InputTest,
        InputTypeTest,
        LabelTest,
        LegendTest,
        NumberTest,
        OpenTest,
        OpenMultipartTest,
        RadioTest,
        SelectTest,
        SelectMultiTest,
        TextareaTest,
        TimeTest;

    public function testUnclosedForm(): void
    {
        $this->expectException(FormException::class);

        $this->view->Form->open();
        $this->view->Form->open();
    }

    public function testInvalidContext(): void
    {
        $this->expectException(FormException::class);

        $this->view->Form->open(new stdClass);
    }

    public function testInvalidInputType(): void
    {
        $this->expectException(FormException::class);

        $this->view->Form->input('input', [
            'type' => 'invalid'
        ]);
    }

    protected function setUp(): void
    {
        CsrfProtection::disable();
        HtmlHelper::setCharset('UTF-8');

        $request = new ServerRequest();
        $response = new ClientResponse();
        $controller = new TestController($request, $response);

        $request->getUri()->setPath('/test');

        $this->view = new View($controller);
    }

}
