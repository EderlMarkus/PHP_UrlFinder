<?php

/**
 * Url Class to handle Database Queries
 * related to the Table "childUrls"
 */
class ChildUrl extends DataObject
{

    /**
     * __construct calls parent construct with table-name "childurls"
     * also checks immediatly if table exists in database, if not create it.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct("childurls");

        //if Table does not yet exist in database create it
        if (!$this->checkIfTableExists()) {
            $this->createTableInDataBase();
        }
    }

    /**
     * checkIfUrlExists checks if the given string exists in database
     *
     * @param  string $url
     *
     * @return boolean true if it does exist, false otherwise
     */
    private function checkIfUrlExists($url)
    {
        $sql = "SELECT * From $this->table WHERE URLParent = (?)";
        $result = $this->executeQuery($sql, [$url]);
        return sizeOf($result) > 0;
    }

    /**
     * createTableInDataBase creates Table in Database
     *
     * @return void
     */
    private function createTableInDataBase()
    {
        $pdo = $this->connection;
        $sql = "CREATE TABLE $this->table ( ID int NOT NULL AUTO_INCREMENT, URLParent varchar(255), isHome boolean, URLChild varchar(255), PRIMARY KEY (ID))";
        $pdo->query($sql);
    }

    /**
     * getChildsByUrl gets all Child-Urls of a URL
     *
     * @param  string $url url you want the childs from
     * @param  boolean $isHome true if you want home-urls, false otherwise
     *
     * @return array of selected values
     */
    private function getChildsByUrl($url, $isHome)
    {
        $sql = "SELECT URLChild FROM $this->table WHERE URLParent = ? AND isHome = ?";
        return $this->executeQuery($sql, [$url, $isHome]);

    }

    /**
     * getHomeUrls same as getChildsByUrl, but only returns Home-Urls
     *
     * @param  string $url you want the childs from
     *
     * @return array of selected values
     */
    public function getHomeUrls($url)
    {
        return $this->getChildsByUrl($url, true);
    }

    /**
     * getForeignUrls same as getChildsByUrl, but only returns Foreign-Urls
     *
     * @param  string $url you want the childs from
     *
     * @return array of selected values
     */
    public function getForeignUrls($url)
    {
        return $this->getChildsByUrl($url, false);
    }

    /**
     * addToDatabase adds lines to database.
     * loops through childurls-array and adds a line for every child given.
     *
     * @param  string $parentUrl parent-url
     * @param  array $childUrl   array of child-urls linked to the parent.
     * @param  boolean $isHome true if thos childs are home-urls false otherwise
     *
     * @return void
     */
    private function addToDatabase($parentUrl, $childUrl, $isHome)
    {
        foreach ($childUrl as $url) {
            $sql = "INSERT INTO $this->table (URLParent, isHome, URLChild) VALUES (?, ?, ?)";
            $this->executeQuery($sql, [$parentUrl, $isHome, $url]);
        }
        return true;
    }

    /**
     * addHomeUrlToDatabase same as addToDatabase but only with Home-Urls
     *
     * @param  string $parentUrl parent-url
     * @param  array $childUrl   array of child-urls linked to the parent.
     *
     * @return void
     */
    public function addHomeUrlToDatabase($parentUrl, $childUrls)
    {

        $this->addToDatabase($parentUrl, $childUrls, true);
    }

    /**
     * addForeignUrlToDatabase same as addToDatabase but only with Foreign-Urls
     *
     * @param  string $parentUrl parent-url
     * @param  array $childUrl   array of child-urls linked to the parent.
     *
     * @return void
     */
    public function addForeignUrlToDatabase($parentUrl, $childUrls)
    {

        $this->addToDatabase($parentUrl, $childUrls, false);
    }

}
