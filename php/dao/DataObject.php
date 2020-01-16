<?php
require_once "Connection.php";

/**
 * DataObject is a Parent-Class that contains
 * Database-Queries that all Child need
 * to prevent redundancy
 */
class DataObject extends Connection
{
    protected $table;

    public function __construct($table)
    {
        parent::__construct();
        $this->setTable($table);
    }

    /**
     * Get the value of table
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Set the value of table
     *
     */
    public function setTable($table)
    {
        $this->table = $table;

    }

    /**
     * getAll gets all Data from this table
     *
     * @return array with the data
     */
    public function getAll()
    {
        $pdo = $this->connection;
        $sql = "SELECT *
        FROM $this->table";

        try {
            $query = $pdo->prepare($sql);
            $query->execute();
            $result = $query->fetchAll();

        } catch (Exception $e) {
            return false;
        }
        return $result;

    }

    /**
     * checkIfTableExists checks if the table exists in database
     * Do you really need a comment for this?
     * @return boolean true if it does, false otherwise
     */
    protected function checkIfTableExists()
    {
        $pdo = $this->connection;
        $sql = "SELECT *
        FROM information_schema.tables
        WHERE table_schema = ?
            AND table_name = ?
        LIMIT 1;";

        try {

            $query = $pdo->prepare($sql);
            $query->execute(array($this->dbname, $this->table));
            $result = $query->fetchAll();

        } catch (Exception $e) {
            return false;
        }

        return sizeOf($result) !== 0;
    }

    /**
     * executeQuery executes SQL-Query, returns fales if exception is catched,
     * but does not throw exception
     * TODO: Put that in the parent, doesnT
     * @param  string $sql SQL-Statement (with PDO Parameters)
     * @param  array $parameters array with PDO-Parameter-Keys
     *
     * @return mixed result if everything worked, false if something went wrong
     */
    protected function executeQuery($sql, $parameters)
    {
        $pdo = $this->connection;
        try {
            $query = $pdo->prepare($sql);
            $query->execute($parameters);
            //fetch only values
            $result = $query->fetchAll(PDO::FETCH_COLUMN);

        } catch (Exception $e) {
            return false;
        }
        return $result;
    }

}
