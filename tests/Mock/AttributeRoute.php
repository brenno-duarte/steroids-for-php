<?php

#[Attribute]
class AttributeRoute
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