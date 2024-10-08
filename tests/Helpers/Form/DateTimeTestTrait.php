<?php
declare(strict_types=1);

namespace Tests\Helpers\Form;

use Fyre\DateTime\DateTime;

trait DateTimeTestTrait
{
    public function testDateTime(): void
    {
        $this->assertSame(
            '<input id="datetime-value" name="datetime_value" type="datetime-local" />',
            $this->view->Form->datetime('datetime_value')
        );
    }

    public function testDateTimeAttributeArray(): void
    {
        $this->assertSame(
            '<input id="datetime" name="datetime" data-test="[1,2]" type="datetime-local" />',
            $this->view->Form->datetime('datetime', [
                'data-test' => [1, 2],
            ])
        );
    }

    public function testDateTimeAttributeEscape(): void
    {
        $this->assertSame(
            '<input id="datetime" name="datetime" data-test="&lt;test&gt;" type="datetime-local" />',
            $this->view->Form->datetime('datetime', [
                'data-test' => '<test>',
            ])
        );
    }

    public function testDateTimeAttributeInvalid(): void
    {
        $this->assertSame(
            '<input class="test" id="datetime" name="datetime" type="datetime-local" />',
            $this->view->Form->datetime('datetime', [
                '*class*' => 'test',
            ])
        );
    }

    public function testDateTimeAttributes(): void
    {
        $this->assertSame(
            '<input class="test" id="other" name="datetime" type="datetime-local" />',
            $this->view->Form->datetime('datetime', [
                'class' => 'test',
                'id' => 'other',
            ])
        );
    }

    public function testDateTimeAttributesOrder(): void
    {
        $this->assertSame(
            '<input class="test" id="other" name="datetime" type="datetime-local" />',
            $this->view->Form->datetime('datetime', [
                'id' => 'other',
                'class' => 'test',
            ])
        );
    }

    public function testDateTimeDot(): void
    {
        $this->assertSame(
            '<input id="key-datetime-value" name="key[datetime_value]" type="datetime-local" />',
            $this->view->Form->datetime('key.datetime_value')
        );
    }

    public function testDateTimeDotDeep(): void
    {
        $this->assertSame(
            '<input id="deep-key-datetime-value" name="deep[key][datetime_value]" type="datetime-local" />',
            $this->view->Form->datetime('deep.key.datetime_value')
        );
    }

    public function testDateTimeId(): void
    {
        $this->assertSame(
            '<input id="other" name="datetime" type="datetime-local" />',
            $this->view->Form->datetime('datetime', [
                'id' => 'other',
            ])
        );
    }

    public function testDateTimeIdFalse(): void
    {
        $this->assertSame(
            '<input name="datetime" type="datetime-local" />',
            $this->view->Form->datetime('datetime', [
                'id' => false,
            ])
        );
    }

    public function testDateTimeIdPrefix(): void
    {
        $this->view->Form->open(null, [
            'idPrefix' => 'test',
        ]);

        $this->assertSame(
            '<input id="test-datetime" name="datetime" type="datetime-local" />',
            $this->view->Form->datetime('datetime')
        );
    }

    public function testDateTimeName(): void
    {
        $this->assertSame(
            '<input id="datetime" name="other" type="datetime-local" />',
            $this->view->Form->datetime('datetime', [
                'name' => 'other',
            ])
        );
    }

    public function testDateTimeNameFalse(): void
    {
        $this->assertSame(
            '<input id="datetime" type="datetime-local" />',
            $this->view->Form->datetime('datetime', [
                'name' => false,
            ])
        );
    }

    public function testDateTimeValueDefault(): void
    {
        $now = DateTime::fromArray([2022, 1, 1]);

        $this->assertSame(
            '<input id="datetime" name="datetime" type="datetime-local" value="2022-01-01T00:00" />',
            $this->view->Form->datetime('datetime', [
                'default' => $now,
            ])
        );
    }

    public function testDateTimeValuePost(): void
    {
        $_POST['datetime'] = '2022-01-01T00:00';

        $this->assertSame(
            '<input id="datetime" name="datetime" type="datetime-local" value="2022-01-01T00:00" />',
            $this->view->Form->datetime('datetime')
        );
    }

    public function testDateTimeValuePostDot(): void
    {
        $_POST['key']['datetime'] = '2022-01-01T00:00';

        $this->assertSame(
            '<input id="key-datetime" name="key[datetime]" type="datetime-local" value="2022-01-01T00:00" />',
            $this->view->Form->datetime('key.datetime')
        );
    }
}
