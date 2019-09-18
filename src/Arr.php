<?php

namespace Pyradic\Platform;

class Arr
{
    public static function cut(array &$array, $values)
    {
        foreach($array as $key => $value){
            if(in_array($value, $values)){
                unset($array[$key]);
            }
        }
        $array = array_values($array);
        return $values;
    }
}
