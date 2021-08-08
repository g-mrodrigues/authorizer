<?php

namespace App\Adapters\Controller;

class AuthorizeController
{
    public function authorize(string $input): string
    {
        return $input . " DEU CERTO";//
    }
}