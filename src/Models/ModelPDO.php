<?php
namespace Models;
use \PDO;

abstract class ModelPDO {
    private static $pdo;

public $host='localhost';
public $db = 'toppack';
public $username = 'root';
public $password = 'ekanshsharma@18';

    protected static function getPDO() {
        if (!isset(self::$pdo)) {
            $dsn = "mysql:host=localhost;dbname=toppack";
            self::$pdo = new PDO($dsn, 'root', 'ekanshsharma@18');
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        return self::$pdo;
    }

    protected static function getModelName() {
        $cla = explode('\\', get_called_class());
        return strtolower($cla[count($cla) - 1]);
    }

    protected static function getTableName() {
        return self::getModelName();
    }

    protected static function getFieldName($field) {
        return self::getModelName() . '_' . $field;
    }

    protected static function getBindName($field) {
        return ":{$field}";
    }

    protected static function getPropertyName($prop) {
        return substr($prop, strlen(self::getModelName()) + 1);
    }

    public static function get($id) {
        return self::getBy('id', $id);
    }

    protected static function getBy($field, $value) {
        $tableName = self::getTableName();
        $fieldName = self::getFieldName($field);
        $bindName = self::getBindName($field);
        $q = "SELECT * FROM {$tableName} ";
        $q .= "WHERE {$fieldName} = {$bindName}";
        $sth = self::getPDO()->prepare($q);
        $sth->bindParam($bindName, $value);
        $sth->execute();
        $data = $sth->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $modelName = self::getModelName();
            return new $modelName($data);
        }
        return null;
    }

    public static function getAll() {
        $tableName = self::getTableName();
        $q = "SELECT * FROM {$tableName} ";
        $sth = self::getPDO()->prepare($q);
        $sth->execute();
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($data) {
            $models = array();
            foreach ($data as $d) {
                $modelName = self::getModelName();
                $models[] = new $modelName($d);
            }
            return $models;
        }
        return null;
    }

    protected static function getAllBy($field, $value) {
        $tableName = self::getTableName();
        $fieldName = self::getFieldName($field);
        $bindName = self::getBindName($field);
        $q = "SELECT * FROM {$tableName} ";
        $q .= "WHERE {$fieldName} = {$bindName}";
        $sth = self::getPDO()->prepare($q);
        $sth->bindValue($bindName, $value);
        $sth->execute();
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($data) {
            $models = array();
            foreach ($data as $d) {
                $modelName = self::getModelName();
                $models[] = new $modelName($d);
            }
            return $models;
        }
        return null;
    }

    private $fields = array();

    public function __construct($schema, $data = false) {
        $this->fields['id'] = array('value' => null, 'type' => PDO::PARAM_INT);
        foreach ($schema as $name => $type) {
            $this->fields[$name] = array('value' => null, 'type' => $type);
        }
        if ($data) {
            foreach ($data as $column => $value) {
                $prop = self::getPropertyName($column);
                $this->fields[$prop]['value'] = $value;
            }
        }
    }
    public function save() {
        $tableName = self::getTableName();
        if ($this->fields['id']['value'] != null) {
            foreach ($this->fields as $field => $f) {
                if ($field != 'id' && $f['value'] != null) {
                    $fieldName = self::getFieldName($field); 
                    $bindName = self::getBindName($field);
                    $fields[] = "{$fieldName} = {$bindName}";
                }
            }
            $fieldName = self::getFieldName('id');
            $bindName = self::getBindName('id');
            $set = implode(', ', $fields);
            $q = "UPDATE {$tableName} ";
            $q .= "SET {$set} ";
            $q .= "WHERE {$fieldName} = {$bindName}";
        } else {
            print_r($this->fields);
            foreach ($this->fields as $field => $f) {
                if ($field != 'id' && $f['value'] != null) {
                    $cols[] = self::getFieldName($field);
                    $binds[] = self::getBindName($field);
                }
            }
            $columns = implode(', ', $cols);
            $bindings = implode(', ', $binds);
            $q = "INSERT INTO {$tableName} ";
            $q .= "({$columns}) VALUES ({$bindings})";
        }
        $sth = ModelPDO::getPDO()->prepare($q);
        foreach ($this->fields as $field => $f) {
            $value = $f['value'];
            if ($f['value'] != null) {
                $sth->bindValue(self::getBindName($field), $f['value'], $f['type']); 
            }
        }
        echo "{$sth->queryString}\n";
        return $sth->execute();
    }
 
    public function __set($name, $value) {
        if (array_key_exists($name, $this->fields)) {
            $this->fields[$name]['value'] = $value;
        }
    }
    public function __get($name) {
        if (array_key_exists($name, $this->fields)) {
            return $this->fields[$name]['value'];
        }
    }
}
?>