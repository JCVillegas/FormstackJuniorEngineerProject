<?php

namespace JCVillegas\JuniorProject;

/**
 *   @ ModelDocument class CRUD document created data in DB.
 */
class ModelDocument
{
    private $database;

    /**
     * Init DB
     */
    public function __construct($database)
    {
        $this->database=$database;
    }

    /**
     * Get a list of all documents
     */
    public function getAllDocuments()
    {
        $this->database->query('SELECT * FROM  '.DatabaseConfig::DB_TABLE);
        $rows = $this->database->resultset();
        return $rows;
    }

}
