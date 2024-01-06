<?php
namespace App\Http\Validation;

interface IValidation
{
    /**
     * Make a bussines validation
     * 
     * @param array $dados 
     * @param int $id 
     * @return mixed 
     */
    public function validate(array $dados, $id = 0);
}
