<?php declare(strict_types=1);

if (!function_exists('reflection_get_attributes')) {
    /**
     * Get function attributes
     *
     * @param object|string $class_name
     * @param string|null $method
     * @param string $attribute_name
     *
     * @return array
     */
    function reflection_get_attributes(
        object|string $class_name,
        string|null $method,
        string $attribute_name
    ): array {
        $attribute_info = [];
        $reflection = new \ReflectionMethod($class_name, $method);

        foreach ($reflection->getAttributes($attribute_name) as $reflection_attribute) {
            $attribute_info = [
                'name' => $reflection_attribute->getName(),
                'args' => $reflection_attribute->getArguments(),
                'instance' => $reflection_attribute->newInstance()
            ];
        }

        return $attribute_info;
    }
}

if (!function_exists('reflection_get_property')) {
    /**
     * Gets a ReflectionProperty for a class's property
     *
     * @param string|object $class
     * @param string $property
     *
     * @return mixed
     */
    function reflection_get_property(string|object $class, string $property): mixed
    {
        $reflection = new \ReflectionClass($class);
        $name = $reflection->getProperty($property);
        return $name->getValue(new $class);
    }
}

if (!function_exists('reflection_extension_info')) {
    /**
     * Reports information about an extension
     *
     * @param string $extension_name
     *
     * @return void
     */
    function reflection_extension_info(string $extension_name): void
    {
        defined('UNDEFINED') || define('UNDEFINED', '%undefined%');

        $message = PHP_EOL;
        $message .= "\e[42m" . str_pad('', 40, pad_type: STR_PAD_BOTH) . "\e[0m" . PHP_EOL;
        $message .= "\e[42;30m" . str_pad('PHP Extension Info: ' . $extension_name, 40, pad_type: STR_PAD_BOTH) . "\e[0m" . PHP_EOL;
        $message .= "\e[42m" . str_pad('', 40, pad_type: STR_PAD_BOTH) . "\e[0m" . PHP_EOL;
        $message .= PHP_EOL;

        echo $message;

        $re = new ReflectionExtension($extension_name);
        $re->info();

        echo "\n\e[92mClassname:\e[0m\n" . PHP_EOL . implode(', ', $re->getClassNames()) ?: UNDEFINED;
        echo PHP_EOL . PHP_EOL;

        if (!empty($re->getConstants())) {
            echo "\e[92mConstants:\e[0m\n";

            foreach ($re->getConstants() as $key => $value) {
                echo "\n{$key}={$value}";
            }

            echo PHP_EOL . PHP_EOL;
        }

        if (!empty($re->getDependencies())) {
            echo "\e[92mDependencies:\e[0m\n";

            foreach ($re->getDependencies() as $key => $value) {
                echo "\n{$key}={$value}";
            }

            echo PHP_EOL . PHP_EOL;
        }

        if (!empty($re->getFunctions())) {
            echo "\e[92mFunctions:\n" . PHP_EOL . implode("\n", array_keys($re->getFunctions())) ?: UNDEFINED;
        }

        if (!empty($re->getINIEntries())) {
            echo "\e[92mINIEntries:\e[0m\n";

            foreach ($re->getINIEntries() as $key => $value) {
                echo "\n{$key}={$value}";
            }

            echo PHP_EOL . PHP_EOL;
        }

        echo "\e[92misPersistent:\e[0m " . $re->isPersistent() ?: UNDEFINED;
        echo PHP_EOL;
        echo "\e[92misTemporary:\e[0m " . $re->isTemporary() ?: UNDEFINED;
    }
}

if (!function_exists('reflection_new_instance')) {
    /**
     * Creates a new class instance from given arguments
     *
     * @param object|string $objectOrClass
     * @param mixed ...$args
     *
     * @return mixed
     */
    function reflection_new_instance(object|string $objectOrClass, ...$args): mixed
    {
        $reflection = new \ReflectionClass($objectOrClass);

        return (!empty($args))
            ? $reflection->newInstanceArgs($args)
            : $reflection->newInstance();
    }
}

if (!function_exists('reflection_instance_without_construct')) {
    /**
     * Creates a new class instance without invoking the constructor
     *
     * @param object|string $objectOrClass
     *
     * @return mixed
     */
    function reflection_instance_without_construct(object|string $objectOrClass): mixed
    {
        $reflection = new \ReflectionClass($objectOrClass);
        return $reflection->newInstanceWithoutConstructor();
    }
}

if (!function_exists('reflection_invoke_method')) {
    /**
     * Invokes a reflected method
     *
     * @param object|string $objectOrClass
     * @param string $method
     * @param mixed ...$args
     *
     * @return mixed
     */
    function reflection_invoke_method(object|string $objectOrClass, string $method, ...$args): mixed
    {
        $reflection = new \ReflectionMethod($objectOrClass, $method);
        $reflection_class = new \ReflectionClass($objectOrClass);

        if ($reflection_class->isAbstract() == true) {
            if (is_object($objectOrClass)) {
                $objectOrClass = get_class($objectOrClass);
            }

            exit('Class ' . $objectOrClass . ' is abstract');
        }

        return (!empty($args))
            ? $reflection->invokeArgs(new $objectOrClass, $args)
            : $reflection->invoke(new $objectOrClass);
    }
}
