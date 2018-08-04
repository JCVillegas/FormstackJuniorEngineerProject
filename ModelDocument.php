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
     * Delete document with specific id
     * @param  int   $documentId
     * @return array $result
     * @throws \Exception
     */
    public function deleteDocument($documentId)
    {
        $documentId['id'] = !empty($documentId['id']) ? (int) $documentId['id'] : 0;
        if ($documentId['id'] == 0) {
            throw new \Exception('Incorrect document id.');
        }
        $this->database->query('DELETE FROM '.DatabaseConfig::DB_TABLE.' WHERE id=:id');
        $this->database->bind(':id', $documentId['id'], \PDO::PARAM_INT);
        $result = $this->database->execute();

        return $result;
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
     * Select document data with specific id
     * @param  int $documentId
     * @return false if empty or array if true
     */
    public function getDocument($documentId)
    {
        $documentId['id'] = !empty($documentId['id']) ? (int) $documentId['id'] : 0;
        if ($documentId['id'] == 0) {
            return false;
        }
        $this->database->query('SELECT * FROM  '.DatabaseConfig::DB_TABLE.' WHERE id=:id');
        $this->database->bind(':id', $documentId['id'], \PDO::PARAM_INT);
        $row = $this->database->resultset();
        if (empty($row)) {
            return false;
        } else {
            return $row[0];
        }
    }

    /**
     * Creates or update documents
     * @param  array $documentData
     * @return array $result
     * @throws \Exception
     */
    public function saveDocument($documentData)
    {
        $documentData['documentName'] = !empty($documentData['documentName']) ? trim(substr($documentData['documentName'], 0, 100)) : '';

        foreach (array_combine($documentData['key'], $documentData['value'])  as $key => $value) {
            $checkKey   = !empty($key) ? trim(substr($key, 0, 100)) : '';
            $checkValue = !empty($value) ? trim(substr($value, 0, 100)) : '';

            if ($checkKey == '' || $checkValue == '' || $documentData['documentName'] == '') {
                throw new \Exception('Incomplete document data.');
            }
        }

        $jsonKeyValue = json_encode(array_combine($documentData['key'], $documentData['value']));

        if (empty($documentData['id'])) {
            $this->database->query('INSERT INTO  ' . DatabaseConfig::DB_TABLE . ' (name, keyvalues, created, updated, exported) 
                  VALUES (:name, :keyvalues, :created, :updated, :exported)');

            $this->database->bind(':name', $documentData['documentName'], \PDO::PARAM_STR);
            $this->database->bind(':keyvalues', $jsonKeyValue, \PDO::PARAM_STR);
            $this->database->bind(':created', date('Y-m-d H:i:s'), \PDO::PARAM_STR);
            $this->database->bind(':updated', '0000-00-00 00:00:00', \PDO::PARAM_STR);
            $this->database->bind(':exported', '0000-00-00 00:00:00', \PDO::PARAM_STR);

            $result = $this->database->execute();
            return $result;
        }
        else {
            $this->database->query('UPDATE '.DatabaseConfig::DB_TABLE.' SET name=:name, keyvalues=:keyvalues,
                  created=:created, updated=:updated, exported=:exported WHERE id=:id');
            $this->database->bind(':id', $documentData['id'], \PDO::PARAM_INT);
            $this->database->bind(':name', $documentData['documentName'], \PDO::PARAM_STR);
            $this->database->bind(':keyvalues', $jsonKeyValue, \PDO::PARAM_STR);
            $this->database->bind(':created', $documentData['created'], \PDO::PARAM_STR);
            $this->database->bind(':updated', date('Y-m-d H:i:s'), \PDO::PARAM_STR);
            $this->database->bind(':exported', $documentData['exported'], \PDO::PARAM_STR);

            $result = $this->database->execute();
            return $result;
        }
    }

    /**
     * Update exports(download) document date
     * @param  int  $Id
     * @return bool $result
     * @throws \Exception
     */
    public function updateExportDocument($Id)
    {
        $documentId = !empty($Id) ? (int) $Id : 0;
        if ($documentId == 0) {
            return false;
        } else {
            $this->database->query('UPDATE '.DatabaseConfig::DB_TABLE.' SET exported=:exported WHERE id=:id');
            $this->database->bind(':id', $documentId, \PDO::PARAM_INT);
            $this->database->bind(':exported', date('Y-m-d H:i:s'), \PDO::PARAM_STR);

            $result = $this->database->execute();
            return $result;
        }
    }
}
