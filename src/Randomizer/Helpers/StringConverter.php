<?php

namespace Randomizer\Helpers;

class StringConverter {

    public static function changeToCamelCase($name): string
    {
        return lcfirst(str_replace('_', '', ucwords($name, '_')));
    }
}