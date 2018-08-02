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

    /**
     * Creates or update documents
     * @param  array $userData
     * @return array $result
     * @throws \Exception
     */
    public function saveDocument($userData)
    {


        $userData['documentName'] = !empty($userData['documentName']) ? trim(substr($userData['documentName'], 0, 100)) : '';

        foreach (array_combine($userData['key'], $userData['value'])  as $key => $value) {
            $checkKey   = !empty($key) ? trim(substr($key, 0, 100)) : '';
            $checkValue = !empty($value) ? trim(substr($value, 0, 100)) : '';

            if ($checkKey == '' || $checkValue == '' || $userData['documentName'] == '') {
                throw new \Exception('Incomplete document data.');
            }
        }

        $jsonKeyValue = json_encode(array_combine($userData['key'], $userData['value']));
        $this->database->query('INSERT INTO  '.DatabaseConfig::DB_TABLE.' (name, keyvalues, created, updated, exported) 
                  VALUES (:name, :keyvalues, :created, :updated, :exported)');

        $this->database->bind(':name', $userData['documentName'], \PDO::PARAM_STR);
        $this->database->bind(':keyvalues', $jsonKeyValue, \PDO::PARAM_STR);
        $this->database->bind(':created', date('Y-m-d H:i:s'), \PDO::PARAM_STR);
        $this->database->bind(':updated', '0000-00-00 00:00:00', \PDO::PARAM_STR);
        $this->database->bind(':exported', '0000-00-00 00:00:00', \PDO::PARAM_STR);

        $result = $this->database->execute();
        return $result;
    }
}
