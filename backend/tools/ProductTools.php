<?php

namespace backend\tools;

class ProductTools
{
    /**
     * Возвращает HTML список групп товаров
     *
     * @param array $arGroups
     * @return string
     */
    public static function getHtmlGroups(array $arGroups): string
    {
        $html = '<ul>';
        foreach ($arGroups as $arGroup) {
            $html .= '<li> <a href="?id='.$arGroup['id'].'">' . $arGroup['name'] . '</a>' . ' (' . $arGroup['count'] . ')';
            if (!empty($arGroup['children'])) {
                $html .= self::getHtmlGroups($arGroup['children']);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * Возврвщает HTML для списка товаров
     *
     * @param $arProducts - массив товаров
     * @return string
     */
    public static function getHtmlProducts($arProducts): string
    {
        $strResult = '';

        foreach ($arProducts as $arProduct) {
            $strResult .= '<p>' . $arProduct['name'] . '</p>';
        }

        return $strResult;
    }
}