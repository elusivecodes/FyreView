<?php
declare(strict_types=1);

namespace Tests\Helpers\Form;

trait LabelTest
{

    public function testLabel(): void
    {
        $this->assertSame(
            '<label for="input-value">Input Value</label>',
            $this->view->Form->label('input_value')
        );
    }

    public function testLabelDot(): void
    {
        $this->assertSame(
            '<label for="key-input-value">Input Value</label>',
            $this->view->Form->label('key.input_value')
        );
    }

    public function testLabelText(): void
    {
        $this->assertSame(
            '<label for="input">Test</label>',
            $this->view->Form->label('input', [
                'text' => 'Test'
            ])
        );
    }

    public function testLabelTextEscape(): void
    {
        $this->assertSame(
            '<label for="input">&lt;i&gt;Test&lt;/i&gt;</label>',
            $this->view->Form->label('input', [
                'text' => '<i>Test</i>'
            ])
        );
    }

    public function testLabelTextNoEscape(): void
    {
        $this->assertSame(
            '<label for="input"><i>Test</i></label>',
            $this->view->Form->label('input', [
                'text' => '<i>Test</i>',
                'escape' => false
            ])
        );
    }

    public function testLabelTextFalse(): void
    {
        $this->assertSame(
            '<label for="input"></label>',
            $this->view->Form->label('input', [
                'text' => false
            ])
        );
    }

    public function testLabelFor(): void
    {
        $this->assertSame(
            '<label for="other">Input</label>',
            $this->view->Form->label('input', [
                'for' => 'other'
            ])
        );
    }

    public function testLabelForFalse(): void
    {
        $this->assertSame(
            '<label>Input</label>',
            $this->view->Form->label('input', [
                'for' => false
            ])
        );
    }

    public function testLabelAttributes(): void
    {
        $this->assertSame(
            '<label class="test" id="label" for="input">Input</label>',
            $this->view->Form->label('input', [
                'class' => 'test',
                'id' => 'label'
            ])
        );
    }

    public function testLabelAttributesOrder(): void
    {
        $this->assertSame(
            '<label class="test" id="label" for="input">Input</label>',
            $this->view->Form->label('input', [
                'id' => 'label',
                'class' => 'test'
            ])
        );
    }

    public function testLabelAttributeInvalid(): void
    {
        $this->assertSame(
            '<label class="test" for="input">Input</label>',
            $this->view->Form->label('input', [
                '*class*' => 'test'
            ])
        );
    }

    public function testLabelAttributeEscape(): void
    {
        $this->assertSame(
            '<label data-test="&lt;test&gt;" for="input">Input</label>',
            $this->view->Form->label('input', [
                'data-test' => '<test>'
            ])
        );
    }

    public function testLabelAttributeArray(): void
    {
        $this->assertSame(
            '<label data-test="[1,2]" for="input">Input</label>',
            $this->view->Form->label('input', [
                'data-test' => [1, 2]
            ])
        );
    }

}
