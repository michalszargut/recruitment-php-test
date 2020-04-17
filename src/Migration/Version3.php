<?php

namespace Snowdog\DevTest\Migration;

use Snowdog\DevTest\Core\Database;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\VarnishManager;
use Snowdog\DevTest\Model\WebsiteManager;

class Version3
{
    /**
     * @var Database|\PDO
     */
    private $database;
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var WebsiteManager
     */
    private $websiteManager;
    /**
     * @var VarnishManager
     */
    private $varnishManager;

    public function __construct(
        Database $database,
        UserManager $userManager,
        WebsiteManager $websiteManager,
        VarnishManager $varnishManager
    ) {
        $this->database = $database;
        $this->userManager = $userManager;
        $this->websiteManager = $websiteManager;
        $this->varnishManager = $varnishManager;
    }

    public function __invoke()
    {
        $this->createPageTable();
        $this->addPageData();
    }

    private function createPageTable()
    {
        $createQuery = <<<SQL
CREATE TABLE `varnishes` (
  `varnish_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`varnish_id`),
  KEY `varnish_id` (`varnish_id`),
  CONSTRAINT `varnish_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
        $this->database->exec($createQuery);
        $createQuery = <<<SQL
CREATE TABLE `websites_varnishes` (
    `website_id` int(11) unsigned NOT NULL,
    `varnish_id` int(11) unsigned NOT NULL,
    PRIMARY KEY (`website_id`, `varnish_id`),
    CONSTRAINT `websites_varnishes_website_fk` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`),
    CONSTRAINT `websites_varnishes_varnish_fk` FOREIGN KEY (`varnish_id`) REFERENCES varnishes(`varnish_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
        $this->database->exec($createQuery);
    }

    private function addPageData()
    {
        $testUser = $this->userManager->getByLogin('test');
        $this->varnishManager->create($testUser, '255.255.255.0');
        $exampleUser = $this->userManager->getByLogin('example');
        $this->varnishManager->create($exampleUser, '255.255.255.0');
        $demoUser = $this->userManager->getByLogin('demo');
        $this->varnishManager->create($demoUser, '255.255.255.0');
    }
}