<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class PageManager
{

    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getByUrl($url)
    {
        $url = "%".$url."%";
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM pages WHERE url LIKE :url');
        $query->bindParam(':url', $url, \PDO::PARAM_STR);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Page::class);
    }

    public function getAllByWebsite(Website $website)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM pages WHERE website_id = :website');
        $query->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Page::class);
    }

    public function create(Website $website, $url)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO pages (url, website_id) VALUES (:url, :website)');
        $statement->bindParam(':url', $url, \PDO::PARAM_STR);
        $statement->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    public function updateVisited(Page $page){
        $pageId = $page->getPageId();

        $statement = $this->database->prepare('UPDATE pages SET last_visited = NOW(), visited_count = visited_count+1 WHERE page_id = :page_id');
        $statement->bindParam(':page_id', $pageId);
        $statement->execute();

        return $this;
    }

    public function getLastRecentlyVisitedPage(User $user){

        $userId = $user->getUserId();
        $query = $this->database->prepare('SELECT p.* FROM pages p LEFT JOIN websites w ON w.website_id=p.website_id LEFT JOIN users u ON w.user_id = u.user_id WHERE u.user_id = :userId ORDER BY p.last_visited DESC');
        $query->setFetchMode(\PDO::FETCH_CLASS, Page::class);
        $query->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(\PDO::FETCH_CLASS);
    }

    public function getMostRecentlyVisitedPage(User $user){
        $userId = $user->getUserId();
        $query = $this->database->prepare('SELECT p.* FROM pages p LEFT JOIN websites w ON w.website_id=p.website_id LEFT JOIN users u ON w.user_id = u.user_id WHERE u.user_id = :userId ORDER BY p.visited_count DESC');
        $query->setFetchMode(\PDO::FETCH_CLASS, Page::class);
        $query->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(\PDO::FETCH_CLASS);
    }
}