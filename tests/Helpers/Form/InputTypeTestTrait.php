<?php
declare(strict_types=1);

namespace Tests\Helpers\Form;

trait InputTypeTestTrait
{

    public function testInputTypeName(): void
    {
        $this->assertSame(
            '<input id="number" name="number" type="number" placeholder="Number" />',
            $this->view->Form->number('number')
        );
    }

    public function testInputTypeAttributes(): void
    {
        $this->assertSame(
            '<input class="test" id="number" name="number" type="number" placeholder="Number" />',
            $this->view->Form->number('number', [
                'class' => 'test',
                'id' => 'number'
            ])
        );
    }

    public function testInputTypeColor(): void
    {
        $this->assertSame(
            '<input id="color" name="color" type="color" />',
            $this->view->Form->color('color')
        );
    }

    public function testInputTypeEmail(): void
    {
        $this->assertSame(
            '<input id="email" name="email" type="email" placeholder="Email" />',
            $this->view->Form->email('email')
        );
    }

    public function testInputTypeFile(): void
    {
        $this->assertSame(
            '<input id="file" name="file" type="file" />',
            $this->view->Form->file('file')
        );
    }

    public function testInputTypeFileValue(): void
    {
        $this->assertSame(
            '<input id="file" name="file" type="file" />',
            $this->view->Form->file('file', [
                'value' => 'test'
            ])
        );
    }

    public function testInputTypeHidden(): void
    {
        $this->assertSame(
            '<input id="hidden" name="hidden" type="hidden" />',
            $this->view->Form->hidden('hidden')
        );
    }

    public function testInputTypeImage(): void
    {
        $this->assertSame(
            '<input id="image" name="image" type="image" />',
            $this->view->Form->image('image')
        );
    }

    public function testInputTypeImageValue(): void
    {
        $this->assertSame(
            '<input id="image" name="image" type="image" />',
            $this->view->Form->image('image', [
                'value' => 'test'
            ])
        );
    }

    public function testInputTypeMonth(): void
    {
        $this->assertSame(
            '<input id="month" name="month" type="month" placeholder="Month" />',
            $this->view->Form->month('month')
        );
    }

    public function testInputTypePassword(): void
    {
        $this->assertSame(
            '<input id="password" name="password" type="password" placeholder="Password" />',
            $this->view->Form->password('password')
        );
    }

    public function testInputTypePasswordValue(): void
    {
        $this->assertSame(
            '<input id="password" name="password" type="password" placeholder="Password" />',
            $this->view->Form->password('password', [
                'value' => 'test'
            ])
        );
    }

    public function testInputTypeRange(): void
    {
        $this->assertSame(
            '<input id="range" name="range" type="range" />',
            $this->view->Form->range('range')
        );
    }

    public function testInputTypeReset(): void
    {
        $this->assertSame(
            '<input id="reset" name="reset" type="reset" />',
            $this->view->Form->reset('reset')
        );
    }

    public function testInputTypeSearch(): void
    {
        $this->assertSame(
            '<input id="search" name="search" type="search" placeholder="Search" />',
            $this->view->Form->search('search')
        );
    }

    public function testInputTypeSubmit(): void
    {
        $this->assertSame(
            '<input id="submit" name="submit" type="submit" />',
            $this->view->Form->submit('submit')
        );
    }

    public function testInputTypeTel(): void
    {
        $this->assertSame(
            '<input id="tel" name="tel" type="tel" placeholder="Tel" />',
            $this->view->Form->tel('tel')
        );
    }

    public function testInputTypeText(): void
    {
        $this->assertSame(
            '<input id="text" name="text" type="text" placeholder="Text" />',
            $this->view->Form->text('text')
        );
    }

    public function testInputTypeUrl(): void
    {
        $this->assertSame(
            '<input id="url" name="url" type="url" placeholder="Url" />',
            $this->view->Form->url('url')
        );
    }

    public function testInputTypeWeek(): void
    {
        $this->assertSame(
            '<input id="week" name="week" type="week" placeholder="Week" />',
            $this->view->Form->week('week')
        );
    }

}
