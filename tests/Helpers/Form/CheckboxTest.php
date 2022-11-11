<?php
declare(strict_types=1);

namespace Tests\Helpers\Form;

trait CheckboxTest
{

    public function testCheckbox(): void
    {
        $this->assertSame(
            '<input name="checkbox_value" type="hidden" value="0" /><input id="checkbox-value" name="checkbox_value" type="checkbox" value="1" />',
            $this->view->Form->checkbox('checkbox_value')
        );
    }

    public function testCheckboxDot(): void
    {
        $this->assertSame(
            '<input name="key[checkbox_value]" type="hidden" value="0" /><input id="key-checkbox-value" name="key[checkbox_value]" type="checkbox" value="1" />',
            $this->view->Form->checkbox('key.checkbox_value')
        );
    }

    public function testCheckboxDotDeep(): void
    {
        $this->assertSame(
            '<input name="deep[key][checkbox_value]" type="hidden" value="0" /><input id="deep-key-checkbox-value" name="deep[key][checkbox_value]" type="checkbox" value="1" />',
            $this->view->Form->checkbox('deep.key.checkbox_value')
        );
    }

    public function testCheckboxName(): void
    {
        $this->assertSame(
            '<input name="other" type="hidden" value="0" /><input id="checkbox" name="other" type="checkbox" value="1" />',
            $this->view->Form->checkbox('checkbox', [
                'name' => 'other'
            ])
        );
    }

    public function testCheckboxNameFalse(): void
    {
        $this->assertSame(
            '<input id="checkbox" type="checkbox" value="1" />',
            $this->view->Form->checkbox('checkbox', [
                'name' => false
            ])
        );
    }

    public function testCheckboxId(): void
    {
        $this->assertSame(
            '<input name="checkbox" type="hidden" value="0" /><input id="other" name="checkbox" type="checkbox" value="1" />',
            $this->view->Form->checkbox('checkbox', [
                'id' => 'other'
            ])
        );
    }

    public function testCheckboxIdFalse(): void
    {
        $this->assertSame(
            '<input name="checkbox" type="hidden" value="0" /><input name="checkbox" type="checkbox" value="1" />',
            $this->view->Form->checkbox('checkbox', [
                'id' => false
            ])
        );
    }

    public function testCheckboxIdPrefix(): void
    {
        $this->view->Form->open(null, [
            'idPrefix' => 'test'
        ]);

        $this->assertSame(
            '<input name="checkbox" type="hidden" value="0" /><input id="test-checkbox" name="checkbox" type="checkbox" value="1" />',
            $this->view->Form->checkbox('checkbox')
        );
    }

    public function testCheckboxValue(): void
    {
        $this->assertSame(
            '<input name="checkbox" type="hidden" value="0" /><input id="checkbox" name="checkbox" type="checkbox" value="on" />',
            $this->view->Form->checkbox('checkbox', [
                'value' => 'on'
            ])
        );
    }

    public function testCheckboxChecked(): void
    {
        $this->assertSame(
            '<input name="checkbox" type="hidden" value="0" /><input id="checkbox" name="checkbox" type="checkbox" value="1" checked />',
            $this->view->Form->checkbox('checkbox', [
                'checked' => true
            ])
        );
    }

    public function testCheckboxCheckedPost(): void
    {
        $this->view->getController()
            ->getRequest()
            ->setGlobals('post', [
                'checkbox' => '1'
            ]);

        $this->assertSame(
            '<input name="checkbox" type="hidden" value="0" /><input id="checkbox" name="checkbox" type="checkbox" value="1" checked />',
            $this->view->Form->checkbox('checkbox')
        );
    }

    public function testCheckboxCheckedPostDot(): void
    {
        $this->view->getController()
            ->getRequest()
            ->setGlobals('post', [
                'key' => [
                    'checkbox' => '1'
                ]
            ]);

        $this->assertSame(
            '<input name="key[checkbox]" type="hidden" value="0" /><input id="key-checkbox" name="key[checkbox]" type="checkbox" value="1" checked />',
            $this->view->Form->checkbox('key.checkbox')
        );
    }

    public function testCheckboxCheckedDefault(): void
    {
        $this->assertSame(
            '<input name="checkbox" type="hidden" value="0" /><input id="checkbox" name="checkbox" type="checkbox" value="1" checked />',
            $this->view->Form->checkbox('checkbox', [
                'default' => '1'
            ])
        );
    }

    public function testCheckboxAttributes(): void
    {
        $this->assertSame(
            '<input name="checkbox" type="hidden" value="0" /><input class="test" id="other" name="checkbox" type="checkbox" value="1" />',
            $this->view->Form->checkbox('checkbox', [
                'class' => 'test',
                'id' => 'other'
            ])
        );
    }

    public function testCheckboxAttributesOrder(): void
    {
        $this->assertSame(
            '<input name="checkbox" type="hidden" value="0" /><input class="test" id="other" name="checkbox" type="checkbox" value="1" />',
            $this->view->Form->checkbox('checkbox', [
                'id' => 'other',
                'class' => 'test'
            ])
        );
    }

    public function testCheckboxAttributeInvalid(): void
    {
        $this->assertSame(
            '<input name="checkbox" type="hidden" value="0" /><input class="test" id="checkbox" name="checkbox" type="checkbox" value="1" />',
            $this->view->Form->checkbox('checkbox', [
                '*class*' => 'test'
            ])
        );
    }

    public function testCheckboxAttributeEscape(): void
    {
        $this->assertSame(
            '<input name="checkbox" type="hidden" value="0" /><input id="checkbox" name="checkbox" data-test="&lt;test&gt;" type="checkbox" value="1" />',
            $this->view->Form->checkbox('checkbox', [
                'data-test' => '<test>'
            ])
        );
    }

    public function testCheckboxAttributeArray(): void
    {
        $this->assertSame(
            '<input name="checkbox" type="hidden" value="0" /><input id="checkbox" name="checkbox" data-test="[1,2]" type="checkbox" value="1" />',
            $this->view->Form->checkbox('checkbox', [
                'data-test' => [1, 2]
            ])
        );
    }

    public function testCheckboxHiddenFieldFalse(): void
    {
        $this->assertSame(
            '<input id="checkbox" name="checkbox" type="checkbox" value="1" />',
            $this->view->Form->checkbox('checkbox', [
                'hiddenField' => false
            ])
        );
    }

}
