<?php

namespace App\Services\Interfaces;

use App\Entities\Usuario;

interface ITransacaoService
{
  public function add(Usuario $pagador, Usuario $recebedor, float $valor): int;
}