<?php

namespace App\Services;

use App\Entities\Transacao;
use App\Entities\Usuario;
use App\Repositories\Interfaces\ITransacaoRepository;
use App\Services\Interfaces\ITransacaoService;
use App\Services\Interfaces\IUsuarioService;

class TransacaoService implements ITransacaoService
{


  public function __construct(
    private ITransacaoRepository $repository,
    private IUsuarioService $usuarioService
  ) {
  }

  public function add(Usuario $pagador, Usuario $recebedor, float $valor): int
  {
    $transacaoObj = new Transacao(
      $pagador,
      $recebedor,
      $valor
    );
    $idTransacao = $this->repository->add($transacaoObj);
    if ($idTransacao) {
      if ($this->usuarioService->retiraSaldo($pagador, $valor)) {
        if ($this->usuarioService->adicionaSaldo($recebedor, $valor)) {
          return $idTransacao;
        }
      }
    }
  }

}