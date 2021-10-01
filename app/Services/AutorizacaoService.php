<?php

namespace App\Services;


use App\Repositories\Interfaces\IAutorizacaoRepository;
use App\Services\Interfaces\IAutorizacaoService;

class AutorizacaoService implements IAutorizacaoService
{

  public function __construct(
    private IAutorizacaoRepository $repository
  ) {
  }


  public function autoriza(): bool
  {
    return $this->repository->autoriza();
  }
}