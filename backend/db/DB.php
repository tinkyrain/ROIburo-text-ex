<?php

namespace backend\db;

include_once "config/ConfigDB.php";

use backend\db\config\ConfigDB;
use Exception;
use PDO;
use PDOException;
use PDOStatement;

class DB
{
    private PDO $obDb;

    public function __construct()
    {
        //Получаем конфиг базы
        $arConfig = ConfigDB::getConfig();
        //Подключаемся к БД
        $this->obDb = new PDO('mysql:host=' . $arConfig['host'] . ';dbname=' . $arConfig['database'], $arConfig['user'], $arConfig['password']);
    }

    /**
     * @param string $stmt
     * @return PDOStatement
     */
    public function query(string $stmt): PDOStatement
    {
        return $this->obDb->query($stmt);
    }

    /**
     * @param string $stmt
     * @return PDOStatement
     */
    public function prepare(string $stmt): PDOStatement
    {
        return $this->obDb->prepare($stmt);
    }

    /**
     * @param string $query
     * @return int
     */
    public function exec(string $query): int
    {
        return $this->obDb->exec($query);
    }

    /**
     * @return string
     */
    public function lastInsertId(): string
    {
        return $this->obDb->lastInsertId();
    }

    /**
     * @param string $query
     * @param array $args
     * @return PDOStatement
     * @throws Exception
     */
    public function run(string $query, array $args = []): PDOStatement
    {
        try {
            if (!$args) {
                return $this->query($query);
            }
            $stmt = $this->prepare($query);
            $stmt->execute($args);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param string $query
     * @param array $args
     * @return mixed
     * @throws Exception
     */
    public function getRow(string $query, array $args = []): mixed
    {
        return $this->run($query, $args)->fetch();
    }

    /**
     * @param string $query
     * @param array $args
     * @return array
     * @throws Exception
     */
    public function getRows(string $query, array $args = []): array
    {
        return $this->run($query, $args)->fetchAll();
    }

    /**
     * @param string $query
     * @param array $args
     * @return mixed
     * @throws Exception
     */
    public function getValue(string $query, array $args = [])
    {
        $result = $this->getRow($query, $args);
        if (!empty($result)) {
            $result = array_shift($result);
        }
        return $result;
    }

    /**
     * @param string $query
     * @param array $args
     * @return array
     * @throws Exception
     */
    public function getColumn(string $query, array $args = []): array
    {
        return $this->run($query, $args)->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * @param string $query
     * @param array $args
     * @throws Exception
     */
    public function sql(string $query, array $args = [])
    {
        $this->run($query, $args);
    }
}