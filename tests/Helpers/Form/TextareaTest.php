<?php
declare(strict_types=1);

namespace Tests\Helpers\Form;

trait TextareaTest
{

    public function testTextarea(): void
    {
        $this->assertSame(
            '<textarea id="textarea-value" name="textarea_value" placeholder="Textarea Value"></textarea>',
            $this->view->Form->textarea('textarea_value')
        );
    }

    public function testTextareaDot(): void
    {
        $this->assertSame(
            '<textarea id="key-textarea-value" name="key[textarea_value]" placeholder="Textarea Value"></textarea>',
            $this->view->Form->textarea('key.textarea_value')
        );
    }

    public function testTextareaDotDeep(): void
    {
        $this->assertSame(
            '<textarea id="deep-key-textarea-value" name="deep[key][textarea_value]" placeholder="Textarea Value"></textarea>',
            $this->view->Form->textarea('deep.key.textarea_value')
        );
    }

    public function testTextareaName(): void
    {
        $this->assertSame(
            '<textarea id="textarea" name="other" placeholder="Textarea"></textarea>',
            $this->view->Form->textarea('textarea', [
                'name' => 'other'
            ])
        );
    }

    public function testTextareaNameFalse(): void
    {
        $this->assertSame(
            '<textarea id="textarea" placeholder="Textarea"></textarea>',
            $this->view->Form->textarea('textarea', [
                'name' => false
            ])
        );
    }

    public function testTextareaId(): void
    {
        $this->assertSame(
            '<textarea id="other" name="textarea" placeholder="Textarea"></textarea>',
            $this->view->Form->textarea('textarea', [
                'id' => 'other'
            ])
        );
    }

    public function testTextareaIdFalse(): void
    {
        $this->assertSame(
            '<textarea name="textarea" placeholder="Textarea"></textarea>',
            $this->view->Form->textarea('textarea', [
                'id' => false
            ])
        );
    }

    public function testTextareaIdPrefix(): void
    {
        $this->view->Form->open(null, [
            'idPrefix' => 'test'
        ]);

        $this->assertSame(
            '<textarea id="test-textarea" name="textarea" placeholder="Textarea"></textarea>',
            $this->view->Form->textarea('textarea')
        );
    }

    public function testTextareaPlaceholder(): void
    {
        $this->assertSame(
            '<textarea id="textarea" name="textarea" placeholder="Other"></textarea>',
            $this->view->Form->textarea('textarea', [
                'placeholder' => 'Other'
            ])
        );
    }

    public function testTextareaPlaceholderFalse(): void
    {
        $this->assertSame(
            '<textarea id="textarea" name="textarea"></textarea>',
            $this->view->Form->textarea('textarea', [
                'placeholder' => false
            ])
        );
    }

    public function testTextareaValue(): void
    {
        $this->assertSame(
            '<textarea id="textarea" name="textarea" placeholder="Textarea">Test</textarea>',
            $this->view->Form->textarea('textarea', [
                'value' => 'Test'
            ])
        );
    }

    public function testTextareaValueEscape(): void
    {
        $this->assertSame(
            '<textarea id="textarea" name="textarea" placeholder="Textarea">&lt;test&gt;</textarea>',
            $this->view->Form->textarea('textarea', [
                'value' => '<test>'
            ])
        );
    }

    public function testTextareaValuePost(): void
    {
        $this->view->getController()
            ->getRequest()
            ->setGlobals('post', [
                'textarea' => 'test'
            ]);

        $this->assertSame(
            '<textarea id="textarea" name="textarea" placeholder="Textarea">test</textarea>',
            $this->view->Form->textarea('textarea')
        );
    }

    public function testTextareaValuePostDot(): void
    {
        $this->view->getController()
            ->getRequest()
            ->setGlobals('post', [
                'key' => [
                    'textarea' => 'test'
                ]
            ]);

        $this->assertSame(
            '<textarea id="key-textarea" name="key[textarea]" placeholder="Textarea">test</textarea>',
            $this->view->Form->textarea('key.textarea')
        );
    }

    public function testTextareaValueDefault(): void
    {
        $this->assertSame(
            '<textarea id="textarea" name="textarea" placeholder="Textarea">test</textarea>',
            $this->view->Form->textarea('textarea', [
                'default' => 'test'
            ])
        );
    }

    public function testTextareaAttributes(): void
    {
        $this->assertSame(
            '<textarea class="test" id="other" name="textarea" placeholder="Textarea"></textarea>',
            $this->view->Form->textarea('textarea', [
                'class' => 'test',
                'id' => 'other'
            ])
        );
    }

    public function testTextareaAttributesOrder(): void
    {
        $this->assertSame(
            '<textarea class="test" id="other" name="textarea" placeholder="Textarea"></textarea>',
            $this->view->Form->textarea('textarea', [
                'id' => 'other',
                'class' => 'test'
            ])
        );
    }

    public function testTextareaAttributeInvalid(): void
    {
        $this->assertSame(
            '<textarea class="test" id="textarea" name="textarea" placeholder="Textarea"></textarea>',
            $this->view->Form->textarea('textarea', [
                '*class*' => 'test'
            ])
        );
    }

    public function testTextareaAttributeEscape(): void
    {
        $this->assertSame(
            '<textarea id="textarea" name="textarea" data-test="&lt;test&gt;" placeholder="Textarea"></textarea>',
            $this->view->Form->textarea('textarea', [
                'data-test' => '<test>'
            ])
        );
    }

    public function testTextareaAttributeArray(): void
    {
        $this->assertSame(
            '<textarea id="textarea" name="textarea" data-test="[1,2]" placeholder="Textarea"></textarea>',
            $this->view->Form->textarea('textarea', [
                'data-test' => [1, 2]
            ])
        );
    }

}
