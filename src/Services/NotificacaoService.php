<?php

namespace App\Services;


use App\Entities\Usuario;
use App\Repositories\Interfaces\INotificacaoRepository;
use App\Services\Interfaces\INotificacaoService;

class NotificacaoService implements INotificacaoService
{

  public function __construct(
    private INotificacaoRepository $repository
  ) {
  }


  public function transacaoRecebida(Usuario $recebedor, float $valor): bool
  {
    return $this->repository->send($recebedor->getNome(), $recebedor->getEmail(), $valor);
  }
}