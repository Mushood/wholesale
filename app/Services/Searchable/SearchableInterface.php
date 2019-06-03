<?php

namespace App\Services\Searchable;

interface SearchableInterface
{
    public static function getCriteria(array &$criteria);

    public static function filter($products, $search);
}
