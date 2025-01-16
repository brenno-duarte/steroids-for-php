<?php

require_once 'vendor/autoload.php';

#[Attribute]
class Route
{
    public function __construct(
        protected string $path
    ) {}

    public function getPath()
    {
        return $this->path;
    }

    public function anotherMethod()
    {
        return 'Another Method';
    }
}

class UsuariosController
{
    private array $property1 = ['value1', 'value2'];

    #[Route("/usuarios")]
    public function index()
    {
        return 'Index';
    }
}

/* $res = reflection_get_attributes(
    UsuariosController::class,
    'index',
    Route::class
);

$instance = new $res["instance"];
var_dump($instance->getPath()); */

//print_r(reflection_get_property(UsuariosController::class, 'property1'));

reflection_extension_info('memcached');

/* $res = reflection_new_instance(UsuariosController::class);
echo $res->index(); */

/* $res = reflection_instance_without_construct(Route::class, 'test');
echo $res->getPath(); */

//print_r(reflection_invoke_method(Route::class, 'anotherMethod'));
