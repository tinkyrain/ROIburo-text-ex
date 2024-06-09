<?php

namespace backend\service;

require "repositories/ProductRepositories.php";

use backend\repositories\ProductRepositories;
use Exception;

class ProductService
{
    //Массив категорий
    private array $arGroups;

    //Массив продуктов
    private array $arProducts;

    //ID категории
    private int $intCategoryId;

    //Репотизорий
    private ProductRepositories $obRepositories;

    /**
     * @throws Exception
     */
    public function __construct(int $intCategoryId = 0)
    {
        $this->obRepositories = new ProductRepositories();
        $this->intCategoryId = $intCategoryId;
        $this->setGroups();
        $this->setProducts();
    }

    /**
     * Возвращает массив групп
     *
     * @return array
     * @throws Exception
     */
    private function fetchGroups(): array
    {
        // Получаем все группы
        $arGroups = $this->getRepositories()->getAllGroup();

        // Создаем массив для хранения групп по их идентификаторам
        $arGroupById = [];

        // Дерево элементов
        $arTree = [];

        // Группы индексируем по идентификатору
        foreach ($arGroups as $arGroup) {
            $arGroupById[$arGroup['id']] = array_merge(
                $arGroup,
                ['count' => $this->countProduct($arGroup['id'])] //Добавляем количество товаров
            );
        }

        // Строим дерево элементов
        foreach ($arGroupById as $id => &$node) {
            if (!$node['id_parent']) {
                // Если нет родителя, добавляем в корень дерева
                $arTree[$id] = &$node;
            } else {
                // Если есть родитель, добавляем в дочерние элементы
                $arGroupById[$node['id_parent']]['children'][$id] = &$node;
            }
        }

        return $arTree;
    }

    /**
     * Считает кол-во товаров в группе
     *
     * @param int $intParentId - ID группы
     * @return int
     * @throws Exception
     */
    private function countProduct(int $intParentId): int
    {
        //Получаем кол-во элементо в текущей группе
        $intProductsCount = count($this->getRepositories()->getProductsByGroupId($intParentId));
        //Получаем дочерние группы
        $arChildGroups = $this->getRepositories()->getGroupsByParentId($intParentId);

        foreach ($arChildGroups as $arChildGroup) {
            //Если дочерние категории есть, то считаем количество в них и прибавляем
            if (!!$arChildGroup['id_parent']) {
                $intProductsCount += $this->countProduct($arChildGroup['id']);
            }
        }

        return $intProductsCount;
    }

    /**
     * Возвращает все товары в категории
     *
     * @param int $intGroupId - ID категории
     * @return array
     * @throws Exception
     */
    private function fetchProducts(int $intGroupId = 0): array
    {
        //Получаем товары
        //Если ID группы больше 0, то получаем по ID группы, в ином случае все товары
        $arProducts = $intGroupId > 0 ?
            $this->getRepositories()->getProductsByGroupId($intGroupId) :
            $this->getRepositories()->getAllProducts();

        //Получаем все дочерние группы
        $arChildGroups = $this->getRepositories()->getGroupsByParentId($intGroupId);

        foreach ($arChildGroups as $arChildGroup) {
            //Если у дочерних групп есть вложенные элементы, то получаем товары из них
            if (!!$arChildGroup['id_parent']) {
                $arProducts = array_merge($arProducts, $this->fetchProducts($arChildGroup['id']));
            }
        }

        return $arProducts;
    }

    /**
     * Геттер для категорий
     *
     * @return array
     */
    public function getGroups(): array
    {
        return $this->arGroups;
    }

    public function getProducts(): array
    {
        return $this->arProducts;
    }

    /**
     * Устанавливает значение категорий
     *
     * @return void
     * @throws Exception
     */
    private function setGroups(): void
    {
        $this->arGroups = $this->fetchGroups();
    }

    /**
     * Устанаваливает значение товаров
     *
     * @return void
     * @throws Exception
     */
    private function setProducts(): void
    {
        $this->arProducts = $this->fetchProducts($this->getCategoriesId());
    }

    /**
     * Возвращает объект репозитория
     *
     * @return ProductRepositories
     */
    private function getRepositories(): ProductRepositories
    {
        return $this->obRepositories;
    }

    /**
     * Возвращает ID выбранной категории
     *
     * @return int
     */
    private function getCategoriesId(): int
    {
        return $this->intCategoryId;
    }
}