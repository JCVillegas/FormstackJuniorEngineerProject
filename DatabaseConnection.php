<?php

namespace JCVillegas\JuniorProject;

/*
*   @ Database class
*/
class DatabaseConnection
{
    private $databaseHandler;
    private $sql;
    /**
     *   @ Class database constructor
     */
    public function __construct()
    {
        $dataSource = 'mysql:host='.DatabaseConfig::DB_HOST.';dbname='.DatabaseConfig::DB_NAME;

        $options = array(
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        );
        $this->databaseHandler = new \PDO($dataSource, DatabaseConfig::DB_USER, DatabaseConfig::DB_PASS, $options);
    }
    /**
     * Receives a query
     * @param  string $query
     * @return void
     */
    public function query($query)
    {
        $this->sql = $this->databaseHandler->prepare($query);
    }

    /**
     *
     * @param  mixed $param Identifier
     * @param  mixed $value Variable name
     * @param  mixed $type  Data type
     * @return void
     */
    public function bind($param, $value, $type)
    {
        $this->sql->bindValue($param, $value, $type);
    }

    /**
     * Executes query
     */
    public function execute()
    {
        return $this->sql->execute();
    }

    /**
     * Returns database result
     * @return array
     */
    public function resultSet()
    {
        $this->execute();

        return $this->sql->fetchAll(\PDO::FETCH_ASSOC);
    }
}
