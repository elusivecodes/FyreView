<?php
declare(strict_types=1);

namespace Tests\Views;

trait DataTestTrait
{
    public function testData(): void
    {
        $this->assertSame(
            $this->view,
            $this->view->setData([
                'a' => 1,
            ])
        );

        $this->assertSame(
            [
                'a' => 1,
            ],
            $this->view->getData()
        );
    }

    public function testDataMerges(): void
    {
        $this->view->setData([
            'a' => 1,
        ]);

        $this->view->setData([
            'b' => 2,
        ]);

        $this->assertSame(
            [
                'a' => 1,
                'b' => 2,
            ],
            $this->view->getData()
        );
    }

    public function testSet(): void
    {
        $this->assertSame(
            $this->view,
            $this->view->set('a', 1)
        );

        $this->assertSame(
            [
                'a' => 1,
            ],
            $this->view->getData()
        );
    }
}
