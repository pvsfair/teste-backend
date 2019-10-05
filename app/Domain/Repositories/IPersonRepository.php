<?php


namespace App\Domain\Repositories;


interface IPersonRepository
{
    //TODO: adicionar __call para poder chamar find com outros parametros alem da indentificacao

    public function find($identification);
    public function getAll();
    public function refunds();
}
