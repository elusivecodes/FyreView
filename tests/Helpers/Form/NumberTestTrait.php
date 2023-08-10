<?php
declare(strict_types=1);

namespace Tests\Helpers\Form;

trait NumberTestTrait
{

    public function testNumber(): void
    {
        $this->assertSame(
            '<input id="number-value" name="number_value" type="number" placeholder="Number Value" />',
            $this->view->Form->number('number_value')
        );
    }

    public function testNumberDot(): void
    {
        $this->assertSame(
            '<input id="key-number-value" name="key[number_value]" type="number" placeholder="Number Value" />',
            $this->view->Form->number('key.number_value')
        );
    }

    public function testNumberDotDeep(): void
    {
        $this->assertSame(
            '<input id="deep-key-number-value" name="deep[key][number_value]" type="number" placeholder="Number Value" />',
            $this->view->Form->number('deep.key.number_value')
        );
    }

    public function testNumberName(): void
    {
        $this->assertSame(
            '<input id="number" name="other" type="number" placeholder="Number" />',
            $this->view->Form->number('number', [
                'name' => 'other'
            ])
        );
    }

    public function testNumberNameFalse(): void
    {
        $this->assertSame(
            '<input id="number" type="number" placeholder="Number" />',
            $this->view->Form->number('number', [
                'name' => false
            ])
        );
    }

    public function testNumberId(): void
    {
        $this->assertSame(
            '<input id="other" name="number" type="number" placeholder="Number" />',
            $this->view->Form->number('number', [
                'id' => 'other'
            ])
        );
    }

    public function testNumberIdFalse(): void
    {
        $this->assertSame(
            '<input name="number" type="number" placeholder="Number" />',
            $this->view->Form->number('number', [
                'id' => false
            ])
        );
    }

    public function testNumberIdPrefix(): void
    {
        $this->view->Form->open(null, [
            'idPrefix' => 'test'
        ]);

        $this->assertSame(
            '<input id="test-number" name="number" type="number" placeholder="Number" />',
            $this->view->Form->number('number')
        );
    }

    public function testNumberPlaceholder(): void
    {
        $this->assertSame(
            '<input id="number" name="number" type="number" placeholder="Other" />',
            $this->view->Form->number('number', [
                'placeholder' => 'Other'
            ])
        );
    }

    public function testNumberPlaceholderFalse(): void
    {
        $this->assertSame(
            '<input id="number" name="number" type="number" />',
            $this->view->Form->number('number', [
                'placeholder' => false
            ])
        );
    }

    public function testNumberValuePost(): void
    {
        $_POST['number'] = '123';

        $this->assertSame(
            '<input id="number" name="number" type="number" value="123" placeholder="Number" />',
            $this->view->Form->number('number')
        );
    }

    public function testNumberValuePostDot(): void
    {
        $_POST['key']['number'] = '123';

        $this->assertSame(
            '<input id="key-number" name="key[number]" type="number" value="123" placeholder="Number" />',
            $this->view->Form->number('key.number')
        );
    }

    public function testNumberValueDefault(): void
    {
        $this->assertSame(
            '<input id="number" name="number" type="number" value="123" placeholder="Number" />',
            $this->view->Form->number('number', [
                'default' => '123'
            ])
        );
    }

    public function testNumberAttributes(): void
    {
        $this->assertSame(
            '<input class="test" id="other" name="number" type="number" placeholder="Number" />',
            $this->view->Form->number('number', [
                'class' => 'test',
                'id' => 'other'
            ])
        );
    }

    public function testNumberAttributesOrder(): void
    {
        $this->assertSame(
            '<input class="test" id="other" name="number" type="number" placeholder="Number" />',
            $this->view->Form->number('number', [
                'id' => 'other',
                'class' => 'test'
            ])
        );
    }

    public function testNumberAttributeInvalid(): void
    {
        $this->assertSame(
            '<input class="test" id="number" name="number" type="number" placeholder="Number" />',
            $this->view->Form->number('number', [
                '*class*' => 'test'
            ])
        );
    }

    public function testNumberAttributeEscape(): void
    {
        $this->assertSame(
            '<input id="number" name="number" data-test="&lt;test&gt;" type="number" placeholder="Number" />',
            $this->view->Form->number('number', [
                'data-test' => '<test>'
            ])
        );
    }

    public function testNumberAttributeArray(): void
    {
        $this->assertSame(
            '<input id="number" name="number" data-test="[1,2]" type="number" placeholder="Number" />',
            $this->view->Form->number('number', [
                'data-test' => [1, 2]
            ])
        );
    }

}
