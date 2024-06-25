<?php
declare(strict_types=1);

namespace Tests\Helpers\Form;

trait SelectMultiTestTrait
{
    public function testSelectMulti(): void
    {
        $this->assertSame(
            '<select id="select-value" name="select_value" multiple></select>',
            $this->view->Form->selectMulti('select_value')
        );
    }

    public function testSelectMultiAttributeArray(): void
    {
        $this->assertSame(
            '<select id="select" name="select" data-test="[1,2]" multiple></select>',
            $this->view->Form->selectMulti('select', [
                'data-test' => [1, 2],
            ])
        );
    }

    public function testSelectMultiAttributeEscape(): void
    {
        $this->assertSame(
            '<select id="select" name="select" data-test="&lt;test&gt;" multiple></select>',
            $this->view->Form->selectMulti('select', [
                'data-test' => '<test>',
            ])
        );
    }

    public function testSelectMultiAttributeInvalid(): void
    {
        $this->assertSame(
            '<select class="test" id="select" name="select" multiple></select>',
            $this->view->Form->selectMulti('select', [
                '*class*' => 'test',
            ])
        );
    }

    public function testSelectMultiAttributes(): void
    {
        $this->assertSame(
            '<select class="test" id="other" name="select" multiple></select>',
            $this->view->Form->selectMulti('select', [
                'class' => 'test',
                'id' => 'other',
            ])
        );
    }

    public function testSelectMultiAttributesOrder(): void
    {
        $this->assertSame(
            '<select class="test" id="other" name="select" multiple></select>',
            $this->view->Form->selectMulti('select', [
                'id' => 'other',
                'class' => 'test',
            ])
        );
    }

    public function testSelectMultiDot(): void
    {
        $this->assertSame(
            '<select id="key-select-value" name="key[select_value]" multiple></select>',
            $this->view->Form->selectMulti('key.select_value')
        );
    }

    public function testSelectMultiDotDeep(): void
    {
        $this->assertSame(
            '<select id="deep-key-select-value" name="deep[key][select_value]" multiple></select>',
            $this->view->Form->selectMulti('deep.key.select_value')
        );
    }

    public function testSelectMultiId(): void
    {
        $this->assertSame(
            '<select id="other" name="select" multiple></select>',
            $this->view->Form->selectMulti('select', [
                'id' => 'other',
            ])
        );
    }

    public function testSelectMultiIdFalse(): void
    {
        $this->assertSame(
            '<select name="select" multiple></select>',
            $this->view->Form->selectMulti('select', [
                'id' => false,
            ])
        );
    }

    public function testSelectMultiIdPrefix(): void
    {
        $this->view->Form->open(null, [
            'idPrefix' => 'test',
        ]);

        $this->assertSame(
            '<select id="test-select" name="select" multiple></select>',
            $this->view->Form->selectMulti('select')
        );
    }

    public function testSelectMultiName(): void
    {
        $this->assertSame(
            '<select id="select" name="other" multiple></select>',
            $this->view->Form->selectMulti('select', [
                'name' => 'other',
            ])
        );
    }

    public function testSelectMultiNameFalse(): void
    {
        $this->assertSame(
            '<select id="select" multiple></select>',
            $this->view->Form->selectMulti('select', [
                'name' => false,
            ])
        );
    }

    public function testSelectMultiOptionGroup(): void
    {
        $this->assertSame(
            '<select id="select" name="select" multiple><optgroup label="test"><option value="0">A</option><option value="1">B</option></optgroup></select>',
            $this->view->Form->selectMulti('select', [
                'options' => [
                    [
                        'label' => 'test',
                        'children' => [
                            'A',
                            'B',
                        ],
                    ],
                ],
            ])
        );
    }

    public function testSelectMultiOptions(): void
    {
        $this->assertSame(
            '<select id="select" name="select" multiple><option value="0">A</option><option value="1">B</option></select>',
            $this->view->Form->selectMulti('select', [
                'options' => [
                    'A',
                    'B',
                ],
            ])
        );
    }

    public function testSelectMultiOptionsAssoc(): void
    {
        $this->assertSame(
            '<select id="select" name="select" multiple><option value="a">A</option></select>',
            $this->view->Form->selectMulti('select', [
                'options' => [
                    'a' => 'A',
                ],
            ])
        );
    }

    public function testSelectMultiOptionsAttributes(): void
    {
        $this->assertSame(
            '<select id="select" name="select" multiple><option value="a">A</option></select>',
            $this->view->Form->selectMulti('select', [
                'options' => [
                    [
                        'value' => 'a',
                        'label' => 'A',
                    ],
                ],
            ])
        );
    }

    public function testSelectMultiOptionsAttributesEscape(): void
    {
        $this->assertSame(
            '<select id="select" name="select" multiple><option data-test="&lt;test&gt;" value="a">A</option></select>',
            $this->view->Form->selectMulti('select', [
                'options' => [
                    [
                        'value' => 'a',
                        'label' => 'A',
                        'data-test' => '<test>',
                    ],
                ],
            ])
        );
    }

    public function testSelectMultiOptionsAttributesInvalid(): void
    {
        $this->assertSame(
            '<select id="select" name="select" multiple><option class="test" value="a">A</option></select>',
            $this->view->Form->selectMulti('select', [
                'options' => [
                    [
                        'value' => 'a',
                        'label' => 'A',
                        '*class*' => 'test',
                    ],
                ],
            ])
        );
    }

    public function testSelectMultiOptionsEscape(): void
    {
        $this->assertSame(
            '<select id="select" name="select" multiple><option value="0">&lt;test&gt;</option></select>',
            $this->view->Form->selectMulti('select', [
                'options' => [
                    '<test>',
                ],
            ])
        );
    }

    public function testSelectMultiSelected(): void
    {
        $this->assertSame(
            '<select id="select" name="select" multiple><option value="0">A</option><option value="1" selected>B</option></select>',
            $this->view->Form->selectMulti('select', [
                'options' => [
                    'A',
                    'B',
                ],
                'value' => 1,
            ])
        );
    }

    public function testSelectMultiSelectedArray(): void
    {
        $this->assertSame(
            '<select id="select" name="select" multiple><option value="0">A</option><option value="1" selected>B</option><option value="2" selected>C</option></select>',
            $this->view->Form->selectMulti('select', [
                'options' => [
                    'A',
                    'B',
                    'C',
                ],
                'value' => [1, 2],
            ])
        );
    }

    public function testSelectMultiSelectedDefault(): void
    {
        $this->assertSame(
            '<select id="select" name="select" multiple><option value="0">A</option><option value="1" selected>B</option><option value="2" selected>C</option></select>',
            $this->view->Form->selectMulti('select', [
                'options' => [
                    'A',
                    'B',
                    'C',
                ],
                'default' => ['1', '2'],
            ])
        );
    }

    public function testSelectMultiSelectedPost(): void
    {
        $_POST['select'] = ['1', '2'];

        $this->assertSame(
            '<select id="select" name="select" multiple><option value="0">A</option><option value="1" selected>B</option><option value="2" selected>C</option></select>',
            $this->view->Form->selectMulti('select', [
                'options' => [
                    'A',
                    'B',
                    'C',
                ],
            ])
        );
    }

    public function testSelectMultiSelectedPostDot(): void
    {
        $_POST['key']['select'] = ['1', '2'];

        $this->assertSame(
            '<select id="key-select" name="key[select]" multiple><option value="0">A</option><option value="1" selected>B</option><option value="2" selected>C</option></select>',
            $this->view->Form->selectMulti('key.select', [
                'options' => [
                    'A',
                    'B',
                    'C',
                ],
            ])
        );
    }
}
