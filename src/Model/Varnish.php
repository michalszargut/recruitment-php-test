<?php


namespace Snowdog\DevTest\Model;


class Varnish
{
    private $varnish_id;
    private $ip;
    private $user_id;


    public function __construct()
    {
        $this->ip = strval($this->ip);
        $this->user_id = intval($this->user_id);
    }

    /**
     * @return int
     */
    public function getVarnishId()
    {
        return $this->varnish_id;
    }

    /**
     * @return string
     */
    public function getIP()
    {
        return $this->ip;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }
}