<?php

namespace App\Helpers;

class UrlHelper
{
    public static function getUrlWithParams(string $url, array $params): string
    {
        $whereArray = [];
        foreach ($params as $propertyKey => $propertyValue) {
            $whereArray[] = "$propertyKey=$propertyValue";
        }

        $where = !empty($whereArray)
            ? '?' . implode('&', $whereArray)
            : '';

        return $url . $where;
    }
}
