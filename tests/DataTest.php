<?php
declare(strict_types=1);

namespace Tests;

trait DataTest
{

    public function testData(): void
    {
        $this->view->setData([
            'a' => 1
        ]);

        $this->assertEquals(
            [
                'a' => 1
            ],
            $this->view->getData()
        );
    }

    public function testDataMerges(): void
    {
        $this->view->setData([
            'a' => 1
        ]);

        $this->view->setData([
            'b' => 2
        ]);

        $this->assertEquals(
            [
                'a' => 1,
                'b' => 2
            ],
            $this->view->getData()
        );
    }

}
