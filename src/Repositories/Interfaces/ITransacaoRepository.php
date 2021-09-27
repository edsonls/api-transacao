<?php

namespace App\Repositories\Interfaces;

use App\Entities\Transacao;

interface ITransacaoRepository
{
  function add(Transacao $usuario): int;

}