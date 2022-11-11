<?php
declare(strict_types=1);

namespace Tests\Helpers\Form;

use
    Fyre\HTMLHelper\HtmlHelper;

trait OpenMultipartTest
{

    public function testOpenMultipart(): void
    {
        $this->assertSame(
            '<form action="/test" method="post" enctype="multipart/form-data" charset="UTF-8">',
            $this->view->Form->openMultipart()
        );
    }

    public function testOpenMultipartAction(): void
    {
        $this->assertSame(
            '<form action="/test/test-method" method="post" enctype="multipart/form-data" charset="UTF-8">',
            $this->view->Form->openMultipart(null, [
                'action' => '/test/test-method'
            ])
        );
    }

    public function testOpenMultipartActionArray(): void
    {
        $this->assertSame(
            '<form action="/test/test-method" method="post" enctype="multipart/form-data" charset="UTF-8">',
            $this->view->Form->openMultipart(null, [
                'action' => [
                    'controller' => 'Test',
                    'action' => 'testMethod'
                ]
            ])
        );
    }

    public function testOpenMultipartCharset(): void
    {
        HtmlHelper::setCharset('ISO-8859-1');

        $this->assertSame(
            '<form action="/test" method="post" enctype="multipart/form-data" charset="ISO-8859-1">',
            $this->view->Form->openMultipart()
        );
    }

    public function testOpenMultipartAttributes(): void
    {
        $this->assertSame(
            '<form class="test" id="form" action="/test" method="post" enctype="multipart/form-data" charset="UTF-8">',
            $this->view->Form->openMultipart(null, [
                'class' => 'test',
                'id' => 'form'
            ])
        );
    }

    public function testOpenMultipartAttributesOrder(): void
    {
        $this->assertSame(
            '<form class="test" id="form" action="/test" method="post" enctype="multipart/form-data" charset="UTF-8">',
            $this->view->Form->openMultipart(null, [
                'id' => 'form',
                'class' => 'test'
            ])
        );
    }

    public function testOpenMultipartAttributeInvalid(): void
    {
        $this->assertSame(
            '<form class="test" action="/test" method="post" enctype="multipart/form-data" charset="UTF-8">',
            $this->view->Form->openMultipart(null, [
                '*class*' => 'test'
            ])
        );
    }

    public function testOpenMultipartAttributeEscape(): void
    {
        $this->assertSame(
            '<form data-test="&lt;test&gt;" action="/test" method="post" enctype="multipart/form-data" charset="UTF-8">',
            $this->view->Form->openMultipart(null, [
                'data-test' => '<test>'
            ])
        );
    }

    public function testOpenMultipartAttributeArray(): void
    {
        $this->assertSame(
            '<form data-test="[1,2]" action="/test" method="post" enctype="multipart/form-data" charset="UTF-8">',
            $this->view->Form->openMultipart(null, [
                'data-test' => [1, 2]
            ])
        );
    }

}
