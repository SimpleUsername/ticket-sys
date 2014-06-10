<?php
class Db{
  public  function  __construct(){
      $this->cdn = DB_DRIVER.':host='.DB_HOST.';dbname='.DB_NAME;
      $this->dbh = new PDO($this->cdn , DB_USER, DB_PASS);
      $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }


    public function sql($query, $params = null) {
        try {
            $result = null;
            $stmt = $this->dbh->prepare($query);
            return $stmt->execute($params);
        } catch(Exception $e) {
            $this->report($e);
        }
    }

    public function insert($table, $fields) {
        try {
            $result = null;
            $names = '';
            $vals = '';
            foreach ($fields as $name => $val) {
                if (isset($names[0])) {
                    $names .= ', ';
                    $vals .= ', ';
                }
                $names .= $name;
                $vals .= ':' . $name;
            }

            $sql = "INSERT  INTO " . $table . ' (' . $names . ') VALUES (' . $vals . ')';
            $rs = $this->dbh->prepare($sql);
            foreach ($fields as $name => $val) {
                $rs->bindValue(':' . $name, $val);
            }
            if ($rs->execute()) {
                $result = $this->dbh->lastInsertId(null);
            }
            return $result;
        } catch(Exception $e) {
            $this->report($e);
        }
    }

    // Returns true/false
    public function update($table, $fields, $where, $params = null) {
        try {
            $sql = 'UPDATE ' . $table . ' SET ';
            $first = true;
            foreach (array_keys($fields) as $name) {
                if (!$first) {
                    $first = false;
                    $sql .= ', ';
                }
                $first = false;
                $sql .= $name . ' = :_' . $name;
            }
            if (!is_array($params)) {
                $params = array();
            }
            $sql .= ' WHERE ' . $where;
            $rs = $this->dbh->prepare($sql);
            foreach ($fields as $name => $val) {
                $params[':_' . $name] = $val;
            }
            $result = $rs->execute($params);
            return $result;
        } catch(Exception $e) {
            $this->report($e);
        }
    }

    public function queryValues($query, $params = null) {
        try {
            $result = null;
            $stmt = $this->db->prepare($query);
            if ($stmt->execute($params)) {
                $result = array();
                while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                    $result[] = $row[0];
                }
            }
            return $result;
        } catch(Exception $e) {
            $this->report($e);
        }
    }
    public function quoteArray($arr) {
        $result = array();
        foreach ($arr as $val) {
            $result[] = $this->db->quote($val);
        }
        return $result;
    }
    public function quote($str) {
        return $this->dbh->quote($str);
    }
    private function report($e) {
        throw $e;
    }
}