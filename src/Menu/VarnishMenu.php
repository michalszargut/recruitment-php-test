<?php


namespace Snowdog\DevTest\Menu;


class VarnishMenu extends AbstractMenu
{
    const PATH = "/varnish";
    public function isActive()
    {
        return $_SERVER['REQUEST_URI'] == self::PATH;
    }

    public function getHref()
    {
        return self::PATH;
    }

    public function getLabel()
    {
        return 'Varnishes';
    }
}