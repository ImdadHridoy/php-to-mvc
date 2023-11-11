<?php
namespace App\System;

use App\System\Database;

class Model extends Database
{
    public static $db;

    public function __construct()
    {
        $instance = Database::getInstance();
        self::$db = $instance->getConnection();
    }

    private function create_database_connection()
    {
        try{
            self::$db = new \PDO("mysql:host={$this->host};dbname={$this->database}", $this->user, $this->password);
            self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        } catch(\PDOException $e){
            return $e->errorInfo[2];
        }
    }


    public static function raw($sql)
    {
        $stmt = self::$db->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        return $stmt->fetchAll();
    }

    public static function read($table = null, $conditionalColumn = null)
    {
        try{
            if (is_array($conditionalColumn)) {
                $whereClause = "";
                foreach ($conditionalColumn as $key => $column) {
                    if (strlen($whereClause)) {
                        $whereClause .= " AND {$key} = '{$column}'";
                    }else{
                        $whereClause .= "{$key} = '{$column}'";
                    }
                }
                $whereClause = rtrim($whereClause, ',');
                $stmt = self::$db->prepare("SELECT * FROM {$table} where {$whereClause}");
                $stmt->execute();
                $stmt->setFetchMode(\PDO::FETCH_OBJ);
                return $stmt->fetchAll();
            } else {
                $stmt = self::$db->prepare("SELECT * FROM {$table}");
                $stmt->execute();
                $stmt->setFetchMode(\PDO::FETCH_OBJ);
                return $stmt->fetchAll();
            }
        } catch(\PDOException $e){
            return $e->errorInfo[2];
        }
    }

    public static function read_one($table = null, $conditionalColumn = null)
    {
        try{
            if (is_array($conditionalColumn)) {
                $whereClause = "";
                foreach ($conditionalColumn as $key => $column) {
                    if (strlen($whereClause)) {
                        $whereClause .= " AND {$key} = '{$column}'";
                    }else{
                        $whereClause .= "{$key} = '{$column}'";
                    }
                }
                // exit($whereClause);
                $stmt = self::$db->prepare("SELECT * FROM {$table} WHERE {$whereClause}");
                $stmt->execute();
                $stmt->setFetchMode(\PDO::FETCH_OBJ);
                return $stmt->fetch();
            }
        } catch(\PDOException $e){
            return $e->errorInfo[2];
        }
    }

    public static function insert($table = null, $data = null)
    {
        try{
            if (is_array($data)) {
                $colums = implode(',', array_keys($data));
                $values = ':' . implode(',:', array_keys($data));

                $stmt = self::$db->prepare("INSERT INTO {$table} ($colums) VALUES ($values)");

                foreach ($data as $key => $value) {
                    $stmt->bindParam(":" . $key, $data[$key]);
                }
                $stmt->execute();

                return true;
            }
            return "No Insert Data Found.";
        } catch(\PDOException $e){
            return $e->errorInfo[2];
        }
    }

    public static function update($table = null, $data = null, $conditionalColumn = null)
    {
        try{
            if (is_array($conditionalColumn)) {
                $whereClause = "";
                foreach ($conditionalColumn as $key => $column) {
                    if (strlen($whereClause)) {
                        $whereClause .= " AND {$key} = {$column}";
                    }else{
                        $whereClause .= "{$key} = {$column}";
                    }
                }
                
                $updateColumn = "";

                foreach ($data as $key => $value) {
                    $updateColumn .= "{$key} = :{$key},";
                }

                $updateColumn = rtrim($updateColumn, ',');

                $stmt = self::$db->prepare("UPDATE {$table} SET $updateColumn where {$whereClause}");

                foreach ($data as $key => $value) {
                    $stmt->bindParam(":" . $key, $data[$key]);
                }
                $stmt->execute();

                return true;
            }
            return "Your query contain update without where clause.";
        } catch(\PDOException $e){
            return $e->errorInfo[2];
        }
    }

    public static function delete($table = null, $conditionalColumn = null)
    {
        try{    
            if (is_array($conditionalColumn)) {
                $whereClause = "";
                foreach ($conditionalColumn as $key => $column) {
                    if (strlen($whereClause)) {
                        $whereClause .= " AND {$key} = '{$column}'";
                    }else{
                        $whereClause .= "{$key} = '{$column}'";
                    }
                }
                // exit($whereClause);
                $stmt = self::$db->prepare("DELETE FROM {$table} where {$whereClause}");
                $stmt->execute();

                return true;
            }
            return "Your query contain delete without where clause.";
        } catch(\PDOException $e){
            return $e->errorInfo[2];
        }
    }
}


new Model();