<?php
declare(strict_types=1);

namespace Tests\Helpers\Form;

trait SelectTest
{

    public function testSelect(): void
    {
        $this->assertSame(
            '<select id="select-value" name="select_value"></select>',
            $this->view->Form->select('select_value')
        );
    }

    public function testSelectDot(): void
    {
        $this->assertSame(
            '<select id="key-select-value" name="key[select_value]"></select>',
            $this->view->Form->select('key.select_value')
        );
    }

    public function testSelectDotDeep(): void
    {
        $this->assertSame(
            '<select id="deep-key-select-value" name="deep[key][select_value]"></select>',
            $this->view->Form->select('deep.key.select_value')
        );
    }

    public function testSelectName(): void
    {
        $this->assertSame(
            '<select id="select" name="other"></select>',
            $this->view->Form->select('select', [
                'name' => 'other'
            ])
        );
    }

    public function testSelectNameFalse(): void
    {
        $this->assertSame(
            '<select id="select"></select>',
            $this->view->Form->select('select', [
                'name' => false
            ])
        );
    }

    public function testSelectId(): void
    {
        $this->assertSame(
            '<select id="other" name="select"></select>',
            $this->view->Form->select('select', [
                'id' => 'other'
            ])
        );
    }

    public function testSelectIdFalse(): void
    {
        $this->assertSame(
            '<select name="select"></select>',
            $this->view->Form->select('select', [
                'id' => false
            ])
        );
    }

    public function testSelectIdPrefix(): void
    {
        $this->view->Form->open(null, [
            'idPrefix' => 'test'
        ]);

        $this->assertSame(
            '<select id="test-select" name="select"></select>',
            $this->view->Form->select('select')
        );
    }

    public function testSelectOptions(): void
    {
        $this->assertSame(
            '<select id="select" name="select"><option value="0">A</option><option value="1">B</option></select>',
            $this->view->Form->select('select', [
                'options' => [
                    'A',
                    'B'
                ]
            ])
        );
    }

    public function testSelectOptionsEscape(): void
    {
        $this->assertSame(
            '<select id="select" name="select"><option value="0">&lt;test&gt;</option></select>',
            $this->view->Form->select('select', [
                'options' => [
                    '<test>'
                ]
            ])
        );
    }

    public function testSelectOptionsAssoc(): void
    {
        $this->assertSame(
            '<select id="select" name="select"><option value="a">A</option></select>',
            $this->view->Form->select('select', [
                'options' => [
                    'a' => 'A'
                ]
            ])
        );
    }

    public function testSelectOptionsAttributes(): void
    {
        $this->assertSame(
            '<select id="select" name="select"><option value="a">A</option></select>',
            $this->view->Form->select('select', [
                'options' => [
                    [
                        'value' => 'a',
                        'label' => 'A'
                    ]
                ]
            ])
        );
    }

    public function testSelectOptionsAttributesInvalid(): void
    {
        $this->assertSame(
            '<select id="select" name="select"><option class="test" value="a">A</option></select>',
            $this->view->Form->select('select', [
                'options' => [
                    [
                        'value' => 'a',
                        'label' => 'A',
                        '*class*' => 'test'
                    ]
                ]
            ])
        );
    }

    public function testSelectOptionsAttributesEscape(): void
    {
        $this->assertSame(
            '<select id="select" name="select"><option data-test="&lt;test&gt;" value="a">A</option></select>',
            $this->view->Form->select('select', [
                'options' => [
                    [
                        'value' => 'a',
                        'label' => 'A',
                        'data-test' => '<test>'
                    ]
                ]
            ])
        );
    }

    public function testSelectOptionGroup(): void
    {
        $this->assertSame(
            '<select id="select" name="select"><optgroup label="test"><option value="0">A</option><option value="1">B</option></optgroup></select>',
            $this->view->Form->select('select', [
                'options' => [
                    [
                        'label' => 'test',
                        'children' => [
                            'A',
                            'B'
                        ]
                    ]
                ]
            ])
        );
    }

    public function testSelectSelected(): void
    {
        $this->assertSame(
            '<select id="select" name="select"><option value="0">A</option><option value="1" selected>B</option></select>',
            $this->view->Form->select('select', [
                'options' => [
                    'A',
                    'B'
                ],
                'value' => 1
            ])
        );
    }

    public function testSelectSelectedPost(): void
    {
        $this->view->getController()
            ->getRequest()
            ->setGlobals('post', [
                'select' => '1'
            ]);

        $this->assertSame(
            '<select id="select" name="select"><option value="0">A</option><option value="1" selected>B</option></select>',
            $this->view->Form->select('select', [
                'options' => [
                    'A',
                    'B'
                ]
            ])
        );
    }

    public function testSelectSelectedPostDot(): void
    {
        $this->view->getController()
            ->getRequest()
            ->setGlobals('post', [
                'key' => [
                    'select' => '1'
                ]
            ]);

        $this->assertSame(
            '<select id="key-select" name="key[select]"><option value="0">A</option><option value="1" selected>B</option></select>',
            $this->view->Form->select('key.select', [
                'options' => [
                    'A',
                    'B'
                ]
            ])
        );
    }

    public function testSelectSelectedDefault(): void
    {
        $this->assertSame(
            '<select id="select" name="select"><option value="0">A</option><option value="1" selected>B</option></select>',
            $this->view->Form->select('select', [
                'options' => [
                    'A',
                    'B'
                ],
                'default' => '1'
            ])
        );
    }

    public function testSelectSelectedWithoutOptions(): void
    {
        $this->assertSame(
            '<select id="select" name="select"><option value="1" selected></option></select>',
            $this->view->Form->select('select', [
                'value' => '1'
            ])
        );
    }

    public function testSelectAttributes(): void
    {
        $this->assertSame(
            '<select class="test" id="other" name="select"></select>',
            $this->view->Form->select('select', [
                'class' => 'test',
                'id' => 'other'
            ])
        );
    }

    public function testSelectAttributesOrder(): void
    {
        $this->assertSame(
            '<select class="test" id="other" name="select"></select>',
            $this->view->Form->select('select', [
                'id' => 'other',
                'class' => 'test'
            ])
        );
    }

    public function testSelectAttributeInvalid(): void
    {
        $this->assertSame(
            '<select class="test" id="select" name="select"></select>',
            $this->view->Form->select('select', [
                '*class*' => 'test'
            ])
        );
    }

    public function testSelectAttributeEscape(): void
    {
        $this->assertSame(
            '<select id="select" name="select" data-test="&lt;test&gt;"></select>',
            $this->view->Form->select('select', [
                'data-test' => '<test>'
            ])
        );
    }

    public function testSelectAttributeArray(): void
    {
        $this->assertSame(
            '<select id="select" name="select" data-test="[1,2]"></select>',
            $this->view->Form->select('select', [
                'data-test' => [1, 2]
            ])
        );
    }

}
