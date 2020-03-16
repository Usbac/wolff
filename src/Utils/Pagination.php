<?php

namespace Wolff\Utils;

class Pagination
{

    const PLACEHOLDER = 'page';

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
     * Set the total number of elements
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
     * Returns the total number of pages
     *
     * @return int the total number of pages
     */
    public function getTotal()
    {
        return $this->total;
    }


    /**
     * Set the number of elements per page
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
     * Returns the total number of elements per page
     *
     * @return int the total number of elements per page
     */
    public function getPageSize()
    {
        return $this->per_page;
    }


    /**
     * Set the current page
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
     * Returns the current page
     *
     * @return int the current page
     */
    public function getPage()
    {
        return $this->page;
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
     * Returns the number of pages that will
     * be beside the current page
     *
     * @return int the number of pages that will
     * be beside the current page
     */
    public function getSidePages()
    {
        return $this->side_pages_n;
    }


    /**
     * Set the pages url
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
     * Returns the pages url
     *
     * @return string the pages url
     */
    public function getUrl()
    {
        return $this->url_format;
    }


    /**
     * Show or not the first and
     * last page
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
     * Returns true if the first and last page
     * will be shown, false otherwise
     *
     * @return bool true if the first and last page
     * will be shown, false otherwise
     */
    public function getShowEnds()
    {
        return $this->show_ends;
    }


    /**
     * Add the first and last page to the pagination array
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
            'url'          => Str::interpolate($this->url_format, [
                self::PLACEHOLDER => $index
            ])
        ];
    }

}
