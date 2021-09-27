<?php

namespace App\Services;

use App\Entities\Enum\TipoUsuarioEnum;
use App\Entities\Transacao;
use App\Entities\Usuario;
use App\Repositories\Interfaces\ITransacaoRepository;
use App\Services\Interfaces\ITransacaoService;
use App\Services\Interfaces\IUsuarioService;
use App\Utils\Errors\ServiceError;

class TransacaoService implements ITransacaoService
{

  public function __construct(
    private ITransacaoRepository $repository,
    private IUsuarioService $usuarioService
  ) {
  }

  public function add(Usuario $pagador, Usuario $recebedor, float $valor): int|ServiceError
  {
    if ($pagador->getTipoUsuario() !== TipoUsuarioEnum::Comum) {
      return new ServiceError(['codigo' => ITransacaoService::PAGADOR_INVALIDO, 'menssagem' => 'Pagador Invalido']);
    }

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
        $this->usuarioService->adicionaSaldo($pagador, $valor);
        $this->delete($idTransacao);
        return new ServiceError(['codigo' => ITransacaoService::PAGADOR_SEM_SALDO, 'menssagem' => 'Pagador Sem Saldo']);
      }
      $this->delete($idTransacao);
      return new ServiceError(['codigo' => ITransacaoService::PAGADOR_SEM_SALDO, 'menssagem' => 'Pagador Sem Saldo']);
    }

    return new ServiceError(['codigo' => ITransacaoService::PAGADOR_SEM_SALDO, 'menssagem' => 'Pagador Sem Saldo']);
  }

  private function delete(int $idTransacao): bool
  {
    return $this->repository->delete($idTransacao);
  }

}