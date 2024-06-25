<?php
declare(strict_types=1);

namespace Tests\Helpers\Form;

trait LegendTestTrait
{
    public function testLegend(): void
    {
        $this->assertSame(
            '<legend></legend>',
            $this->view->Form->legend()
        );
    }

    public function testLegendAttributeArray(): void
    {
        $this->assertSame(
            '<legend data-test="[1,2]"></legend>',
            $this->view->Form->legend('', [
                'data-test' => [1, 2],
            ])
        );
    }

    public function testLegendAttributeEscape(): void
    {
        $this->assertSame(
            '<legend data-test="&lt;test&gt;"></legend>',
            $this->view->Form->legend('', [
                'data-test' => '<test>',
            ])
        );
    }

    public function testLegendAttributeInvalid(): void
    {
        $this->assertSame(
            '<legend class="test"></legend>',
            $this->view->Form->legend('', [
                '*class*' => 'test',
            ])
        );
    }

    public function testLegendAttributes(): void
    {
        $this->assertSame(
            '<legend class="test" id="legend"></legend>',
            $this->view->Form->legend('', [
                'class' => 'test',
                'id' => 'legend',
            ])
        );
    }

    public function testLegendAttributesOrder(): void
    {
        $this->assertSame(
            '<legend class="test" id="legend"></legend>',
            $this->view->Form->legend('', [
                'id' => 'legend',
                'class' => 'test',
            ])
        );
    }

    public function testLegendContent(): void
    {
        $this->assertSame(
            '<legend>Test</legend>',
            $this->view->Form->legend('Test')
        );
    }

    public function testLegendContentEscape(): void
    {
        $this->assertSame(
            '<legend>&lt;i&gt;Test&lt;/i&gt;</legend>',
            $this->view->Form->legend('<i>Test</i>')
        );
    }

    public function testLegendContentNoEscape(): void
    {
        $this->assertSame(
            '<legend><i>Test</i></legend>',
            $this->view->Form->legend('<i>Test</i>', [
                'escape' => false,
            ])
        );
    }
}
