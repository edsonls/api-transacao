<?php

namespace App\Services;

use App\Entities\Enum\TipoUsuarioEnum;
use App\Entities\Transacao;
use App\Entities\Usuario;
use App\Repositories\Interfaces\ITransacaoRepository;
use App\Services\Interfaces\IAutorizacaoService;
use App\Services\Interfaces\ITransacaoService;
use App\Services\Interfaces\IUsuarioService;
use App\Utils\Errors\ServiceError;

class TransacaoService implements ITransacaoService
{

  public function __construct(
    private ITransacaoRepository $repository,
    private IUsuarioService $usuarioService,
    private IAutorizacaoService $autorizacaoService,
  ) {
  }

  public function add(Usuario $pagador, Usuario $recebedor, float $valor): int|ServiceError
  {
    $transacaoValida = $this->validaTransacao($pagador, $valor);
    if ($transacaoValida === true) {
      $transacaoEntity = new Transacao($pagador, $recebedor, $valor);
      $idTransacao = $this->repository->add($transacaoEntity);
      if ($idTransacao) {
        if ($this->usuarioService->retiraSaldo($pagador, $valor)) {
          if ($this->usuarioService->adicionaSaldo($recebedor, $valor)) {
            $this->enviaNotificacao($recebedor, $valor);
            return $idTransacao;
          }
          $this->usuarioService->estornarSaldo($pagador, $valor);
          $this->delete($idTransacao);
          return new ServiceError(
            ['codigo' => ITransacaoService::TRANSACAO_INVALIDA, 'menssagem' => 'Erro ao ao tentar transacionar']
          );
        }
        $this->delete($idTransacao);
        return new ServiceError(['codigo' => ITransacaoService::TRANSACAO_INVALIDA, 'menssagem' => 'Pagador Sem Saldo']
        );
      }
    }
    return $transacaoValida;
  }

  private function delete(int $idTransacao): bool
  {
    return $this->repository->delete($idTransacao);
  }

  private function validaTransacao(Usuario $pagador, float $valor): ServiceError|bool
  {
    if ($pagador->getTipoUsuario() !== TipoUsuarioEnum::Comum) {
      return new ServiceError(['codigo' => ITransacaoService::PAGADOR_INVALIDO, 'menssagem' => 'Pagador Invalido']);
    }
    if (!$this->autorizacaoService->autoriza()) {
      return new ServiceError(['codigo' => ITransacaoService::NAO_AUTORIZADO, 'menssagem' => 'Transacao não Autorizada']
      );
    }
    if (!$this->usuarioService->validarSaldoPagador($pagador, $valor)) {
      return new ServiceError(['codigo' => ITransacaoService::PAGADOR_SEM_SALDO, 'menssagem' => 'Pagador Sem Saldo']);
    }
    return true;
  }

  private function enviaNotificacao(Usuario $recebedor, float $valor): void
  {
    //todo
  }
}