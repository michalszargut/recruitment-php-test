<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\Page;
use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\User;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;

class IndexAction
{

    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    /**
     * @var User
     */
    private $user;
    /**
     * @var PageManager
     */
    private $pageManager;

    public function __construct(UserManager $userManager, WebsiteManager $websiteManager, PageManager $pageManager)
    {
        $this->websiteManager = $websiteManager;
        if (isset($_SESSION['login'])) {
            $this->user = $userManager->getByLogin($_SESSION['login']);
        }
        $this->pageManager = $pageManager;
    }

    protected function getWebsites()
    {
        if($this->user) {
            return $this->websiteManager->getAllByUser($this->user);
        } 
        return [];
    }

    protected function getPagesCount(){
        if($this->user){
            return $this->websiteManager->getCountOfUserPages($this->user);
        }
        return null;
    }
    protected function getLeastRecentlyVisitedPage()
    {
        if($this->user) {
            return $this->pageManager->getLastRecentlyVisitedPage($this->user);
        }
        return null;
    }
    protected function getMostRecentlyVisitedPage()
    {
        if($this->user) {
            return $this->pageManager->getMostRecentlyVisitedPage($this->user);
        }
        return null;
    }

    public function execute()
    {
        require __DIR__ . '/../view/index.phtml';
    }
}