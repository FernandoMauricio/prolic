<?php

namespace app\helpers;

class UtfHelper
{
    public static function decode($value)
    {
        if (is_string($value)) {
            return mb_convert_encoding($value, 'UTF-8', 'Windows-1252');
        }
        return $value;
    }
}
