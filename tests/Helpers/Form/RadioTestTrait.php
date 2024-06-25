<?php
declare(strict_types=1);

namespace Tests\Helpers\Form;

trait RadioTestTrait
{
    public function testRadio(): void
    {
        $this->assertSame(
            '<input id="radio-value" name="radio_value" type="radio" value="1" />',
            $this->view->Form->radio('radio_value', [
                'value' => '1',
            ])
        );
    }

    public function testRadioAttributeArray(): void
    {
        $this->assertSame(
            '<input id="radio" name="radio" data-test="[1,2]" type="radio" value="1" />',
            $this->view->Form->radio('radio', [
                'value' => '1',
                'data-test' => [1, 2],
            ])
        );
    }

    public function testRadioAttributeEscape(): void
    {
        $this->assertSame(
            '<input id="radio" name="radio" data-test="&lt;test&gt;" type="radio" value="1" />',
            $this->view->Form->radio('radio', [
                'value' => '1',
                'data-test' => '<test>',
            ])
        );
    }

    public function testRadioAttributeInvalid(): void
    {
        $this->assertSame(
            '<input class="test" id="radio" name="radio" type="radio" value="1" />',
            $this->view->Form->radio('radio', [
                'value' => '1',
                '*class*' => 'test',
            ])
        );
    }

    public function testRadioAttributes(): void
    {
        $this->assertSame(
            '<input class="test" id="other" name="radio" type="radio" value="1" />',
            $this->view->Form->radio('radio', [
                'class' => 'test',
                'id' => 'other',
                'value' => '1',
            ])
        );
    }

    public function testRadioAttributesOrder(): void
    {
        $this->assertSame(
            '<input class="test" id="other" name="radio" type="radio" value="1" />',
            $this->view->Form->radio('radio', [
                'id' => 'other',
                'class' => 'test',
                'value' => '1',
            ])
        );
    }

    public function testRadioChecked(): void
    {
        $this->assertSame(
            '<input id="radio" name="radio" type="radio" value="1" checked />',
            $this->view->Form->radio('radio', [
                'value' => '1',
                'checked' => true,
            ])
        );
    }

    public function testRadioCheckedDefault(): void
    {
        $this->assertSame(
            '<input id="radio" name="radio" type="radio" value="1" checked />',
            $this->view->Form->radio('radio', [
                'value' => '1',
                'default' => '1',
            ])
        );
    }

    public function testRadioCheckedPost(): void
    {
        $_POST['radio'] = '1';

        $this->assertSame(
            '<input id="radio" name="radio" type="radio" value="1" checked />',
            $this->view->Form->radio('radio', [
                'value' => '1',
            ])
        );
    }

    public function testRadioCheckedPostDot(): void
    {
        $_POST['key']['radio'] = '1';

        $this->assertSame(
            '<input id="key-radio" name="key[radio]" type="radio" value="1" checked />',
            $this->view->Form->radio('key.radio', [
                'value' => '1',
            ])
        );
    }

    public function testRadioDot(): void
    {
        $this->assertSame(
            '<input id="key-radio-value" name="key[radio_value]" type="radio" value="1" />',
            $this->view->Form->radio('key.radio_value', [
                'value' => '1',
            ])
        );
    }

    public function testRadioDotDeep(): void
    {
        $this->assertSame(
            '<input id="deep-key-radio-value" name="deep[key][radio_value]" type="radio" value="1" />',
            $this->view->Form->radio('deep.key.radio_value', [
                'value' => '1',
            ])
        );
    }

    public function testRadioId(): void
    {
        $this->assertSame(
            '<input id="other" name="radio" type="radio" value="1" />',
            $this->view->Form->radio('radio', [
                'id' => 'other',
                'value' => '1',
            ])
        );
    }

    public function testRadioIdFalse(): void
    {
        $this->assertSame(
            '<input name="radio" type="radio" value="1" />',
            $this->view->Form->radio('radio', [
                'id' => false,
                'value' => '1',
            ])
        );
    }

    public function testRadioIdPrefix(): void
    {
        $this->view->Form->open(null, [
            'idPrefix' => 'test',
        ]);

        $this->assertSame(
            '<input id="test-radio" name="radio" type="radio" value="1" />',
            $this->view->Form->radio('radio', [
                'value' => '1',
            ])
        );
    }

    public function testRadioName(): void
    {
        $this->assertSame(
            '<input id="radio" name="other" type="radio" value="1" />',
            $this->view->Form->radio('radio', [
                'name' => 'other',
                'value' => '1',
            ])
        );
    }

    public function testRadioNameFalse(): void
    {
        $this->assertSame(
            '<input id="radio" type="radio" value="1" />',
            $this->view->Form->radio('radio', [
                'name' => false,
                'value' => '1',
            ])
        );
    }

    public function testRadioValue(): void
    {
        $this->assertSame(
            '<input id="radio" name="radio" type="radio" value="on" />',
            $this->view->Form->radio('radio', [
                'value' => 'on',
            ])
        );
    }
}
