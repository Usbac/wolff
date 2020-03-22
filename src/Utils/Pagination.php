<?php

namespace Wolff\Utils;

class Pagination
{

    const PLACEHOLDER = '{page}';

    /**
     * The total of elements.
     *
     * @var int
     */
    private $total;

    /**
     * The total of elements per page.
     *
     * @var int
     */
    private $per_page;

    /**
     * The current page.
     *
     * @var int
     */
    private $page;

    /**
     * The number of pages that will
     * be beside the current page.
     *
     * @var int
     */
    private $side_pages_n;

    /**
     * The URL format of the pages.
     *
     * @var string
     */
    private $url_format;

    /**
     * Show or not the first and last page.
     *
     * @var bool
     */
    private $show_ends;


    /**
     * Constructor
     *
     * @param  int  $total  the total of elements
     * @param  int  $per_page  the total of elements per page
     * @param  int  $page  the current page
     * @param  int  $side_pages_n  the number of pages that will
     * be beside the current page
     */
    public function __construct(int $total = 0,
                                int $per_page = 0,
                                int $page = 0,
                                int $side_pages_n = 5,
                                string $url_format)
    {
        $this->total = $total;
        $this->per_page = $per_page;
        $this->page = $page;
        $this->side_pages_n = $side_pages_n;
        $this->url_format = $url_format;
        $this->show_ends = true;
    }


    /**
     * Returns the pagination array
     *
     * @return array the pagination array
     */
    public function get()
    {
        $total_pages = ceil($this->total / $this->per_page);
        $begin = $this->page - $this->side_pages_n;
        $end = $this->page + $this->side_pages_n;
        $pagination = [];

        if ($begin <= 0) {
            $begin = 1;
        }

        for ($i = $begin; $i <= $end && $i <= $total_pages; $i++) {
            $pagination[] = $this->getNewPage($i);
        }

        if ($this->show_ends) {
            $this->addEnds($pagination);
        }

        return $pagination;
    }


    /**
     * Sets the total number of elements
     *
     * @param  int  $total  the total number of elements
     *
     * @return Pagination this
     */
    public function setTotal(int $total)
    {
        $this->total = $total;

        return $this;
    }


    /**
     * Sets the number of elements per page
     *
     * @param  int  $per_page  the number of elements per page
     *
     * @return Pagination this
     */
    public function setPageSize(int $per_page)
    {
        $this->per_page = $per_page;

        return $this;
    }


    /**
     * Sets the current page
     *
     * @param  int  $page  the current page
     *
     * @return Pagination this
     */
    public function setPage(int $page = 0)
    {
        $this->page = $page;

        return $this;
    }


    /**
     * Set the number of pages that will
     * be beside the current page
     *
     * @param  int  $side_pages_n  the number of pages that will
     * be beside the current page
     *
     * @return Pagination this
     */
    public function setSidePages(int $side_pages_n = 5)
    {
        $this->side_pages_n = $side_pages_n;

        return $this;
    }


    /**
     * Sets the pages url
     * The placeholder for the page number in the string
     * must have the following format: {page}
     *
     * @param  string  $url_format  the pages url
     *
     * @return Pagination this
     */
    public function setUrl(string $url_format)
    {
        $this->url_format = $url_format;

        return $this;
    }


    /**
     * Sets if the first and
     * last page will be shown
     *
     * @param  bool  $show_ends  true for showing the first
     * and last page in the pagination, false for not showing it
     *
     * @return Pagination this
     */
    public function showEnds(bool $show_ends = true)
    {
        $this->show_ends = $show_ends;

        return $this;
    }


    /**
     * Adds the first and last page to the pagination array
     *
     * @param  array  &$pagination  the pagination array
     */
    private function addEnds(array &$pagination)
    {
        if ($pagination[0]['index'] != 1) {
            array_unshift($pagination, $this->getNewPage(1));
        }

        $total_pages = ceil($this->total / $this->per_page);

        if (end($pagination)['index'] != $total_pages) {
            $pagination[] = $this->getNewPage($total_pages);
        }
    }


    /**
     * Returns a new page based in the given index
     * in the form of an associative array
     *
     * @param  int  $index  the page index
     *
     * @return array A new page based in the given index
     */
    private function getNewPage(int $index)
    {
        return [
            'index'        => $index,
            'current_page' => $index === $this->page,
            'url'          => \str_replace(self::PLACEHOLDER, $index, $this->url_format)
        ];
    }

}
