<?php

namespace backend\repositories;

include_once './db/DB.php';

use backend\db\DB;
use Exception;

class ProductRepositories
{
    private DB $obDb;

    public function __construct()
    {
        $this->obDb = new DB();
    }

    /**
     * Возвращает группы по ID родителя
     *
     * @throws Exception
     */
    public function getGroupsByParentId(int $intParentId = 0): array
    {
        return $this->obDb->getRows("SELECT * FROM `groups` WHERE id_parent = ?", [$intParentId]);
    }

    /**
     * Возвращает все группы
     *
     * @throws Exception
     */
    public function getAllGroup(): array
    {
        return $this->obDb->getRows('SELECT * FROM `groups`', []);
    }

    /**
     * Возвращает все товары
     *
     * @throws Exception
     */
    public function getAllProducts(): array
    {
        return $this->obDb->getRows('SELECT * FROM `products`', []);
    }

    /**
     * @param int $intId - ID группы
     * @return array
     * @throws Exception
     */
    public function getProductsByGroupId(int $intId = 0): array
    {
        return $this->obDb->getRows("SELECT * FROM `products` WHERE id_group = ?", [$intId]);
    }
}