<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class VarnishManager
{

    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAllByUser(User $user)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM varnishes WHERE user_id = :userId');
        $query->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Varnish::class);
    }

    public function getWebsites(Varnish $varnish)
    {
        $varnishId = $varnish->getVarnishId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT w.* FROM varnishes v LEFT JOIN websites_varnishes wv ON wv.varnish_id = v.varnish_id LEFT JOIN websites w ON w.website_id = wv.website_id WHERE v.varnish_id = :varnishId');
        $query->bindParam(':varnishId', $varnishId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Website::class);
    }

    public function getByWebsite(Website $website)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM varnishes v LEFT JOIN websites_varnishes wv ON wv.varnish_id = v.varnish_id WHERE wv.website_id = :websiteId');
        $query->bindParam(':websiteId', $websiteId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Varnish::class);
    }

    public function create(User $user, $ip)
    {
        $userId = $user->getUserId();

        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO varnishes (ip, user_id) VALUES (:ip, :userId)');
        $statement->bindParam(':ip', $ip, \PDO::PARAM_STR);
        $statement->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    public function link($varnish, $website)
    {
        $statement = $this->database->prepare('INSERT INTO websites_varnishes (varnish_id, website_id) VALUES (:varnishId, :websiteId)');
        $statement->bindParam(':varnishId', $varnish, \PDO::PARAM_INT);
        $statement->bindParam(':websiteId', $website, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function unlink($varnish, $website)
    {
        $statement = $this->database->prepare('DELETE FROM websites_varnishes WHERE website_id=:websiteId AND varnish_id=:varnishId');
        $statement->bindParam(':websiteId', $website, \PDO::PARAM_INT);
        $statement->bindParam(':varnishId', $varnish, \PDO::PARAM_INT);
        $statement->execute();
    }

}