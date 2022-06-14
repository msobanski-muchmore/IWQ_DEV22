<?php


namespace App\Helper;


class Converter
{
    public static function convertEntityToArray($entity): array {
        $reflect = new \ReflectionClass($entity);
        $fields  = $reflect->getProperties(\ReflectionProperty::IS_PRIVATE);
        $vars = array_map(function($f){
            return $f->getName();
        },$fields);

        $data = [];

        foreach($vars as $index){
            $data[$index] = $entity->{$index};
        }

        return $data;
    }
}
