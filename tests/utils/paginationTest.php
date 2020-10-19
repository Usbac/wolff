<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Utils\Pagination;

class PaginationTest extends TestCase
{

    private $pagination;


    public function setUp(): void
    {
        $this->pagination = new Pagination(
            50, //Total of elements
            10, //Elements per page
            3, //Current page
            4, //Number of side pages
            'blog/{page}' //Url format
        );
    }


    public function testInit()
    {
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

        $this->assertEquals($expected_array, $this->pagination->get());

        $this->pagination->setTotal(100);
        $this->pagination->setPageSize(10);
        $this->pagination->setPage(5);
        $this->pagination->setSidePages(1);
        $this->pagination->setUrl('blog/posts/{page}');
        $this->pagination->showEnds(true);

        $expected_array = [
            [
                'index'        => 1,
                'current_page' => false,
                'url'          => 'blog/posts/1'
            ],
            [
                'index'        => 4,
                'current_page' => false,
                'url'          => 'blog/posts/4'
            ],
            [
                'index'        => 5,
                'current_page' => true,
                'url'          => 'blog/posts/5'
            ],
            [
                'index'        => 6,
                'current_page' => false,
                'url'          => 'blog/posts/6'
            ],
            [
                'index'        => 10,
                'current_page' => false,
                'url'          => 'blog/posts/10'
            ],
        ];

        $this->assertEquals($expected_array, $this->pagination->get());
    }
}
