<?php

declare(strict_types=1);

if (!function_exists('object_get_properties')) {
    /**
     * Get the public properties of an object.
     * This is an alias of `get_object_vars`, except if will always return public properties only.
     *
     * @param object $object
     * @param bool   $dynamic  Get properties not defined in the class
     * @return array
     */
    function object_get_properties($object, bool $dynamic = false): array
    {
        expect_type($object, 'object');
        $data = get_object_vars($object);

        if (!$dynamic) {
            $class = get_class($object);
            $data = array_intersect_key($data, get_class_vars($class));
        }

        return $data;
    }
}

if (!function_exists('object_set_properties')) {
    /**
     * Set the public properties of an object
     *
     * @param object $object
     * @param array  $data
     * @param bool   $dynamic  Set properties not defined in the class
     * @return void
     */
    function object_set_properties($object, array $data, bool $dynamic = false): void
    {
        expect_type($object, 'object');

        if (!$dynamic) {
            $class = get_class($object);
            $data = array_intersect_key($data, get_class_vars($class));
        }

        foreach ($data as $key => $value) {
            $object->$key = $value;
        }
    }
}

if (!function_exists('object_to_array')) {
    /**
     * Convert object to the array.
     *
     * @param object $object PHP object
     *
     * @throws \Exception
     *
     * @return array
     */
    function object_to_array(object $object): array
    {
        return json_decode(json_encode($object), true);
    }
}

if (!function_exists('array_to_object')) {
    /**
     * Convert array to the object.
     *
     * @param array $array PHP array
     *
     * @throws \Exception
     *
     * @return object
     */
    function array_to_object(array $array): object
    {
        $object = new \stdClass();
        
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $name => $value) {
                (is_array($value)) ? $object->{$name} = array_to_object($value) : $object->{$name} = $value;
            }
        }

        return $object;
    }
}
