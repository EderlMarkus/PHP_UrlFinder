<?php
require_once "DataObject.php";

/**
 * Url Class to handle Database Queries
 * related to the Table "urls"
 */
class Url extends DataObject
{
    /**
     * __construct calls parent construct with table-name "urls"
     * also checks immediatly if table exists in database, if not create it.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct("urls");

        //if Table does not yet exist in database create it
        if (!$this->checkIfTableExists()) {
            $this->createTableInDataBase();
        }
    }

    /**
     * createTableInDataBase creates table in database
     *
     * @return void
     */
    private function createTableInDataBase()
    {
        $pdo = $this->connection;
        $sql = "CREATE TABLE $this->table  ( ID int NOT NULL AUTO_INCREMENT, URLs varchar(255), PRIMARY KEY (ID))";
        $pdo->query($sql);
    }

    /**
     * getByUrl gets line by Url
     *
     * @param  string $url
     *
     * @return array with the data
     */
    public function getByUrl($url)
    {
        $sql = "SELECT URLs From $this->table WHERE URLs = ?";
        return $this->executeQuery($sql, [$url]);

    }

    /**
     * checkIfUrlExists checks if URL exists in database
     *
     * @param  string $url
     *
     * @return boolean true if it exists false otherwise
     */
    public function checkIfUrlExists($url)
    {
        $sql = "SELECT * FROM $this->table WHERE URLs = (?)";
        $result = $this->executeQuery($sql, [$url]);

        return sizeOf($result) > 0;
    }

    /**
     * addToDataBase adds a url to the database
     *
     * @param  string $url you want to add
     *
     * @return boolean true if everything worked, false otherwise
     */
    public function addToDataBase($url)
    {
        $sql = "INSERT INTO $this->table (URLs) VALUES (?)";
        return ($this->executeQuery($sql, [$url]) !== false);

    }

}
