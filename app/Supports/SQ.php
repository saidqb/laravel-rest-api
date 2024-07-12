<?php

namespace App\Supports;

class SQ
{
    use Concerns\HasResponse;
    use Concerns\HasQuery;
    use Concerns\HasHelper;

    /**
     * Make instance of class
     *
     * @param string $class
     * @return object
     * Avilable: QueryFilter
     */
    static function make($class = null)
    {
        $make = "App\\Supports\\Make\\$class";
        return new $make;
    }
}
