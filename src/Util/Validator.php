<?php


namespace SedoxVDb\Util;


class Validator
{
    public static function validateId($id)
    {
        return $id && is_numeric($id);
    }
}
