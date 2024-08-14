<?php
declare(strict_types=1);

namespace Tests\Helpers\Form;

use Fyre\DateTime\DateTime;

trait DateTestTrait
{
    public function testDate(): void
    {
        $this->assertSame(
            '<input id="date-value" name="date_value" type="date" />',
            $this->view->Form->date('date_value')
        );
    }

    public function testDateAttributeArray(): void
    {
        $this->assertSame(
            '<input id="date" name="date" data-test="[1,2]" type="date" />',
            $this->view->Form->date('date', [
                'data-test' => [1, 2],
            ])
        );
    }

    public function testDateAttributeEscape(): void
    {
        $this->assertSame(
            '<input id="date" name="date" data-test="&lt;test&gt;" type="date" />',
            $this->view->Form->date('date', [
                'data-test' => '<test>',
            ])
        );
    }

    public function testDateAttributeInvalid(): void
    {
        $this->assertSame(
            '<input class="test" id="date" name="date" type="date" />',
            $this->view->Form->date('date', [
                '*class*' => 'test',
            ])
        );
    }

    public function testDateAttributes(): void
    {
        $this->assertSame(
            '<input class="test" id="other" name="date" type="date" />',
            $this->view->Form->date('date', [
                'class' => 'test',
                'id' => 'other',
            ])
        );
    }

    public function testDateAttributesOrder(): void
    {
        $this->assertSame(
            '<input class="test" id="other" name="date" type="date" />',
            $this->view->Form->date('date', [
                'id' => 'other',
                'class' => 'test',
            ])
        );
    }

    public function testDateDot(): void
    {
        $this->assertSame(
            '<input id="key-date-value" name="key[date_value]" type="date" />',
            $this->view->Form->date('key.date_value')
        );
    }

    public function testDateDotDeep(): void
    {
        $this->assertSame(
            '<input id="deep-key-date-value" name="deep[key][date_value]" type="date" />',
            $this->view->Form->date('deep.key.date_value')
        );
    }

    public function testDateId(): void
    {
        $this->assertSame(
            '<input id="other" name="date" type="date" />',
            $this->view->Form->date('date', [
                'id' => 'other',
            ])
        );
    }

    public function testDateIdFalse(): void
    {
        $this->assertSame(
            '<input name="date" type="date" />',
            $this->view->Form->date('date', [
                'id' => false,
            ])
        );
    }

    public function testDateIdPrefix(): void
    {
        $this->view->Form->open(null, [
            'idPrefix' => 'test',
        ]);

        $this->assertSame(
            '<input id="test-date" name="date" type="date" />',
            $this->view->Form->date('date')
        );
    }

    public function testDateName(): void
    {
        $this->assertSame(
            '<input id="date" name="other" type="date" />',
            $this->view->Form->date('date', [
                'name' => 'other',
            ])
        );
    }

    public function testDateNameFalse(): void
    {
        $this->assertSame(
            '<input id="date" type="date" />',
            $this->view->Form->date('date', [
                'name' => false,
            ])
        );
    }

    public function testDateValueDefault(): void
    {
        $now = DateTime::fromArray([2022, 1, 1]);

        $this->assertSame(
            '<input id="date" name="date" type="date" value="2022-01-01" />',
            $this->view->Form->date('date', [
                'default' => $now,
            ])
        );
    }

    public function testDateValuePost(): void
    {
        $_POST['date'] = '2022-01-01';

        $this->assertSame(
            '<input id="date" name="date" type="date" value="2022-01-01" />',
            $this->view->Form->date('date')
        );
    }

    public function testDateValuePostDot(): void
    {
        $_POST['key']['date'] = '2022-01-01';

        $this->assertSame(
            '<input id="key-date" name="key[date]" type="date" value="2022-01-01" />',
            $this->view->Form->date('key.date')
        );
    }
}
