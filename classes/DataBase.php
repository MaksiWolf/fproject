<?php


class Database
{
    private static $instance = NULL;

    private $DATABASETYPE;
    private $DATABASE;
    private $USER;
    private $PASSWORD;
    private $dbconn;
    protected $query;
    protected $error = false;
    protected $results = [];
    protected $count = 0;

    private function __construct()
    {
        $this->DATABASE = $DATABASE;
        $this->USER = $USER;
        $this->PASSWORD = $PASSWORD;
        $this->DATABASETYPE = $DATABASETYPE;

        try {
            $this->dbconn = new PDO(Config::get('db.type') . ":dbname=".Config::get('db.database'), Config::get('db.username'), Config::get('db.password'), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getAll($table)
    {
        return $this->get($table, ['id', '>', 0]); //костыль :)
    }

    public function getOne($table, $id)
    {
        return $this->get($table, ['id', '=', $id]);
    }

    public function insert($table, $data = [])
    {
        $keys = implode(array_keys($data), "`, `");
        $values = implode(array_values($data), "', '");
        $query = "INSERT INTO `" . $table . "`(`" . $keys . "`) VALUES ('" . $values . "')";
        if (!$this->query($query)->error()) {
            return true;
        }
        return false;
    }

    public function update($table, $id, $data = [])
    {
        $res = array_map(function ($k, $v) {
            return "`$k` = $v ";
        }, array_keys($data), $data);
        $res = implode(',', $res);
        $query = "UPDATE `" . $table . "` SET " . $res . " WHERE id=" . $id;
        if (!$this->query($query)->error()) {
            return true;
        }
        return false;
    }

    public function query($sql, $params = [])
    {
        $this->error = false;
        $this->query = $this->dbconn->prepare($sql);

        if (count($params)) {
            foreach ($params as $key => $param) {
                $this->query->bindValue($key + 1, $param);
            }
        }
        if (!$this->query->execute()) {
            $this->error = true;
        } else {
            try {
                $this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
                $this->count = $this->query->rowCount();
            } catch (PDOException $e) {

            }
        }
        return $this;
    }

    public function first()
    {
        return $this->results()[0];
    }

    public function error()
    {
        return $this->error;
    }

    public function results()
    {
        return $this->results;
    }

    public function count()
    {
        return $this->count;
    }

    public function get($table, $where = [])
    {
        return $this->action('SELECT *', $table, $where);
    }

    public function delete($table, $where = [])
    {
        return $this->action('DELETE', $table, $where);
    }


    public function action($action, $table, $where = [])
    {
        if (count($where) === 3) {
            $operators = ['=', '>', '<', '<=', '>='];
            $filed = $where[0];
            $operator = $where[1];
            $value = $where[2];
            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$filed} {$operator} ?";
                if (!$this->query($sql, [$value])->error()) {
                    return $this;
                }
            }
        }
        return false;
    }
}
