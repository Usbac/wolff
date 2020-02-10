<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Utilities\Pagination;

class paginationTest extends TestCase
{

    public function testInit()
    {
        $pagination = new \Utilities\Pagination(
            50, //Total of elements
            10, //Elements per page
            3, //Current page
            4, //Number of side pages
            'blog/{page}' //Url format
        );

        $expected_array = [
            [
                'index'        => 1,
                'current_page' => false,
                'url'          => 'blog/1'
            ],
            [
                'index'        => 2,
                'current_page' => false,
                'url'          => 'blog/2'
            ],
            [
                'index'        => 3,
                'current_page' => true,
                'url'          => 'blog/3'
            ],
            [
                'index'        => 4,
                'current_page' => false,
                'url'          => 'blog/4'
            ],
            [
                'index'        => 5,
                'current_page' => false,
                'url'          => 'blog/5'
            ],
        ];

        $result = $pagination->get();
        $pagination->setTotal(100);
        $pagination->setPageSize(10);
        $pagination->setPage(5);
        $pagination->setSidePages(3);
        $pagination->setUrl('blog/posts/{page}');
        $pagination->showEnds(true);

        $this->assertEquals($expected_array, $result);
        $this->assertEquals(100, $pagination->getTotal());
        $this->assertEquals(10, $pagination->getPageSize());
        $this->assertEquals(5, $pagination->getPage());
        $this->assertEquals(3, $pagination->getSidePages());
        $this->assertEquals('blog/posts/{page}', $pagination->getUrl());
        $this->assertTrue($pagination->getShowEnds());
    }

}
