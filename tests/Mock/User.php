<?php

class User
{
    #[AttributeRoute("/users")]
    public function index()
    {
        return 'Index';
    }
}
