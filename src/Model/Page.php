<?php

namespace Snowdog\DevTest\Model;

class Page
{

    public $page_id;
    public $url;
    public $website_id;
    public $last_visited;
    public $visited_count;

    public function __construct()
    {
        $this->website_id = intval($this->website_id);
        $this->page_id = intval($this->page_id);

    }

    /**
     * @return int
     */
    public function getPageId()
    {
        return $this->page_id;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getWebsiteId()
    {
        return $this->website_id;
    }

    /**
     * @return null|\DateTime
     */
    public function getLastVisited(){

        return new \DateTime($this->last_visited);
    }
    /**
     * @return int
     */
    public function getVisitedCount()
    {
        return $this->visited_count;
    }
}