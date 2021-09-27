<?php

namespace App\Services;

use App\Entities\Transacao;
use App\Entities\Usuario;
use App\Repositories\Interfaces\ITransacaoRepository;
use App\Services\Interfaces\ITransacaoService;

class TransacaoService implements ITransacaoService
{


  public function __construct(
    private ITransacaoRepository $repository
  ) {
  }

  public function add(Usuario $pagador, Usuario $recebedor, float $valor): int
  {
    $transacaoObj = new Transacao(
      $pagador,
      $recebedor,
      $valor
    );
    return $this->repository->add($transacaoObj);
  }
}