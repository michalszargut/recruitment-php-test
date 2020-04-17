<?php

namespace Snowdog\DevTest\Command;

use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;
use Symfony\Component\Console\Output\OutputInterface;

class ParseSitemapCommand
{
    const PATH = __DIR__.'/../../web/sitemaps/';
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

    public function __construct(UserManager $userManager, WebsiteManager $websiteManager, PageManager $pageManager)
    {
        $this->websiteManager = $websiteManager;
        $this->pageManager = $pageManager;
        $this->userManager = $userManager;
    }

    public function __invoke($userId, OutputInterface $output)
    {
        $user = $this->userManager->getById($userId);
        if(!$user){
            $output->writeln("<error>User with id: ".$userId." not found</error>");
        }
        $output->writeln("<info>Searching for sitemap in path ".self::PATH."</info>");

        $files = scandir(self::PATH, SCANDIR_SORT_DESCENDING);
        $lastAddedFile = current(array_diff($files, array('..', '.')));
        if(!$lastAddedFile){
            $output->writeln("<error>Can't find any file in directory!</error>");
            return;
        }
        $lastAddedFileName = $lastAddedFile;
        $lastAddedFile = file_get_contents(self::PATH.$lastAddedFile);
        $lastAddedFile = simplexml_load_string($lastAddedFile);
        if(empty($lastAddedFile)){
            $output->writeln("<error>File ".$lastAddedFileName." is empty!</error>");
            return;
        }else{
            $output->writeln("<info>Found file: ".$lastAddedFileName."</info>");
        }
        foreach ($lastAddedFile as $url){
            $url = parse_url(current($url->loc));
            $url['host'] = str_replace('www.','', $url['host']);
            $website = $this->websiteManager->getByHostName($url['host']);
            if(!$website){
               $website = $this->websiteManager->create($user, $url['host'], $url['host']);
            }
            $path = ltrim($url['path'].((key_exists('query', $url))? "?".$url['query']: ""),'/');
            if(!$this->pageManager->getByUrl($path)){
                $this->pageManager->create($website, $path);
            }
        }
        $output->writeln("<info>Done!</info>");
    }
}