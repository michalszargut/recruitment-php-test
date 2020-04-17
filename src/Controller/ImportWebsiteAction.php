<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;

class ImportWebsiteAction
{
    /**
     * @var WebsiteManager
     */
    private $websiteManager;
    /**
     * @var PageManager
     */
    private $pageManager;
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var \Snowdog\DevTest\Model\User
     */
    private $user;

    public function __construct(UserManager $userManager, WebsiteManager $websiteManager, PageManager $pageManager)
    {
        $this->websiteManager = $websiteManager;
        $this->pageManager = $pageManager;
        $this->userManager = $userManager;
        if (isset($_SESSION['login'])) {
            $this->user = $userManager->getByLogin($_SESSION['login']);
        }
    }

    public function execute()
    {
       $file = $_FILES['sitemap'];
       $tmpName = $file['tmp_name'];
       $content = file_get_contents($tmpName);
       $content = simplexml_load_string($content);
       if(empty($content)){
           $_SESSION['flash'] = 'Uploaded file is empty.';
       }
        foreach ($content as $url){
            $url = parse_url(current($url->loc));
            $url['host'] = str_replace('www.','', $url['host']);
            $website = $this->websiteManager->getByHostName($url['host']);
            if(!$website){
                $website = $this->websiteManager->create($this->user, $url['host'], $url['host']);
            }
            $path = ltrim($url['path'].((key_exists('query', $url))? "?".$url['query']: ""),'/');
            if(!$this->pageManager->getByUrl($path)){
                $this->pageManager->create($website, $path);
            }
        }
       header('Location: /');
    }
}