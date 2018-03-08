<?php

namespace AdminBundle\Service;

/**
 * Service handling pagination from request
 */

class Paginator
{
    private $page;
    private $pagesCount;
    private $itemsCount;
    private $itemsPerPage;
    private $limit;
    private $offset;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->page         = 1;
        $this->itemsPerPage = 20;
        $this->itemsCount   = 0;
        $this->pagesCount   = 0;
        $this->limit        = 0;
        $this->offset       = 0;
    }
    /**
     * Return the current page number, items per page, max pages, limit and offset
     *
     * @param       $count
     * @param       $page
     * @param       $perPage
     *
     * @return Paginator
     */
    public function handlePagination($count, $page = 0, $perPage = 20)
    {
        $this->page = $page;
        $this->itemsPerPage = $perPage;
        $this->itemsCount = $count;
        if ($this->itemsCount == 0) {
            $this->pagesCount = 0;
        } else {
            $this->pagesCount = ceil($this->itemsCount / $this->itemsPerPage);
        }
        if ($this->pagesCount == 0) {
            $this->pagesCount = 1;
        }
        if ($this->page > $this->pagesCount) {
            $this->page = $this->pagesCount;
        }
        $this->limit  = $this->itemsPerPage;
        $this->offset = (($this->page > 0 ? $this->page : 1) - 1) * $this->itemsPerPage;
        return $this;
    }
    /**
     * Return current page
     *
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }
    /**
     * Returns maximum page count
     *
     * @return int
     */
    public function getPagesCount()
    {
        return $this->pagesCount;
    }
    /**
     * Returns the number of elements
     *
     * @return int
     */
    public function getItemsCount()
    {
        return $this->itemsCount;
    }
    /**
     * Returns the number of elements per page
     *
     * @return int
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }
    /**
     * Returns the limit to use in a query
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }
    /**
     * Returns the offset to youse in a query
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }
}