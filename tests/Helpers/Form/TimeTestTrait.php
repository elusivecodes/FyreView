<?php
declare(strict_types=1);

namespace Tests\Helpers\Form;

use Fyre\DateTime\DateTime;

trait TimeTestTrait
{
    public function testTime(): void
    {
        $this->assertSame(
            '<input id="time-value" name="time_value" type="time" placeholder="Time Value" />',
            $this->view->Form->time('time_value')
        );
    }

    public function testTimeAttributeArray(): void
    {
        $this->assertSame(
            '<input id="time" name="time" data-test="[1,2]" type="time" placeholder="Time" />',
            $this->view->Form->time('time', [
                'data-test' => [1, 2],
            ])
        );
    }

    public function testTimeAttributeEscape(): void
    {
        $this->assertSame(
            '<input id="time" name="time" data-test="&lt;test&gt;" type="time" placeholder="Time" />',
            $this->view->Form->time('time', [
                'data-test' => '<test>',
            ])
        );
    }

    public function testTimeAttributeInvalid(): void
    {
        $this->assertSame(
            '<input class="test" id="time" name="time" type="time" placeholder="Time" />',
            $this->view->Form->time('time', [
                '*class*' => 'test',
            ])
        );
    }

    public function testTimeAttributes(): void
    {
        $this->assertSame(
            '<input class="test" id="other" name="time" type="time" placeholder="Time" />',
            $this->view->Form->time('time', [
                'class' => 'test',
                'id' => 'other',
            ])
        );
    }

    public function testTimeAttributesOrder(): void
    {
        $this->assertSame(
            '<input class="test" id="other" name="time" type="time" placeholder="Time" />',
            $this->view->Form->time('time', [
                'id' => 'other',
                'class' => 'test',
            ])
        );
    }

    public function testTimeDot(): void
    {
        $this->assertSame(
            '<input id="key-time-value" name="key[time_value]" type="time" placeholder="Time Value" />',
            $this->view->Form->time('key.time_value')
        );
    }

    public function testTimeDotDeep(): void
    {
        $this->assertSame(
            '<input id="deep-key-time-value" name="deep[key][time_value]" type="time" placeholder="Time Value" />',
            $this->view->Form->time('deep.key.time_value')
        );
    }

    public function testTimeId(): void
    {
        $this->assertSame(
            '<input id="other" name="time" type="time" placeholder="Time" />',
            $this->view->Form->time('time', [
                'id' => 'other',
            ])
        );
    }

    public function testTimeIdFalse(): void
    {
        $this->assertSame(
            '<input name="time" type="time" placeholder="Time" />',
            $this->view->Form->time('time', [
                'id' => false,
            ])
        );
    }

    public function testTimeIdPrefix(): void
    {
        $this->view->Form->open(null, [
            'idPrefix' => 'test',
        ]);

        $this->assertSame(
            '<input id="test-time" name="time" type="time" placeholder="Time" />',
            $this->view->Form->time('time')
        );
    }

    public function testTimeName(): void
    {
        $this->assertSame(
            '<input id="time" name="other" type="time" placeholder="Time" />',
            $this->view->Form->time('time', [
                'name' => 'other',
            ])
        );
    }

    public function testTimeNameFalse(): void
    {
        $this->assertSame(
            '<input id="time" type="time" placeholder="Time" />',
            $this->view->Form->time('time', [
                'name' => false,
            ])
        );
    }

    public function testTimePlaceholder(): void
    {
        $this->assertSame(
            '<input id="time" name="time" type="time" placeholder="Other" />',
            $this->view->Form->time('time', [
                'placeholder' => 'Other',
            ])
        );
    }

    public function testTimePlaceholderFalse(): void
    {
        $this->assertSame(
            '<input id="time" name="time" type="time" />',
            $this->view->Form->time('time', [
                'placeholder' => false,
            ])
        );
    }

    public function testTimeValueDefault(): void
    {
        $now = DateTime::fromArray([2022, 1, 1]);

        $this->assertSame(
            '<input id="time" name="time" type="time" value="00:00" placeholder="Time" />',
            $this->view->Form->time('time', [
                'default' => $now,
            ])
        );
    }

    public function testTimeValuePost(): void
    {
        $_POST['time'] = '00:00';

        $this->assertSame(
            '<input id="time" name="time" type="time" value="00:00" placeholder="Time" />',
            $this->view->Form->time('time')
        );
    }

    public function testTimeValuePostDot(): void
    {
        $_POST['key']['time'] = '00:00';

        $this->assertSame(
            '<input id="key-time" name="key[time]" type="time" value="00:00" placeholder="Time" />',
            $this->view->Form->time('key.time')
        );
    }
}
