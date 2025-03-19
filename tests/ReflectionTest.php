<?php

require_once 'Mock/AttributeRoute.php';
require_once 'Mock/User.php';

use PHPUnit\Framework\TestCase;

class ReflectionTest extends TestCase
{
    public function testGetAttributes()
    {
        $result = reflection_get_attributes(
            User::class,
            'index',
            AttributeRoute::class
        );

        $this->assertEquals('AttributeRoute', $result["name"]);
        $this->assertEquals(['/users'], $result["args"]);
        $this->assertEquals('/users', $result["instance"]->getPath());
    }

    public function testGetProperty()
    {
        $class = new class {
            private array $property1 = ['value1', 'value2'];
        };

        $this->assertEquals([
            'value1',
            'value2'
        ], reflection_get_property($class, 'property1'));
    }

    public function testNewInstance()
    {
        $class = new class('') {
            public function __construct(private string $name = 'default') {}

            public function index()
            {
                return 'Name: ' . $this->name;
            }
        };

        $instance = reflection_new_instance($class, 'Brenno');

        $this->assertEquals('Name: Brenno', $instance->index());
    }

    public function testNewInstanceWithoutConstructor()
    {
        $class = new class('') {
            private string $name = 'default';

            public function __construct(private string $other) {}

            public function index()
            {
                return 'Name: ' . $this->name;
            }
        };

        $instance = reflection_instance_without_construct($class);

        $this->assertEquals('Name: default', $instance->index());
    }

    public function testInvokeMethod()
    {
        $class = new class {
            public function index()
            {
                return 'Index method';
            }
        };

        $instance = reflection_invoke_method($class, 'index');

        $this->assertEquals('Index method', $instance);
    }
}
