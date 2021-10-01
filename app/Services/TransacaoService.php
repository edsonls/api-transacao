<?php

namespace App\Services;

use App\Entities\Enum\TipoUsuarioEnum;
use App\Entities\Transacao;
use App\Entities\Usuario;
use App\Repositories\Interfaces\ITransacaoRepository;
use App\Services\Enum\ErrorServiceEnum;
use App\Services\Interfaces\IAutorizacaoService;
use App\Services\Interfaces\INotificacaoService;
use App\Services\Interfaces\ITransacaoService;
use App\Services\Interfaces\IUsuarioService;
use App\Utils\Errors\ServiceError;

class TransacaoService implements ITransacaoService
{

  public function __construct(
    private ITransacaoRepository $repository,
    private IUsuarioService $usuarioService,
    private IAutorizacaoService $autorizacaoService,
    private INotificacaoService $notificaService
  ) {
  }

  public function add(Usuario $pagador, Usuario $recebedor, float $valor): int|ServiceError
  {
    $transacaoValida = $this->validaTransacao($pagador, $valor);

    if ($transacaoValida === true) {
      $transacaoEntity = new Transacao($pagador, $recebedor, $valor);
      $idTransacao = $this->repository->add($transacaoEntity);

      if (
        $idTransacao &&
        $this->usuarioService->retiraSaldo($pagador, $valor)) {
        if ($this->usuarioService->adicionaSaldo($recebedor, $valor)) {
          $this->enviaNotificacao($recebedor, $valor);
          return $idTransacao;
        }
        $this->usuarioService->estornarSaldo($pagador, $valor);
        $this->delete($idTransacao);
        return new ServiceError(
          ['codigo' => ErrorServiceEnum::TransacaoInvalida, 'mensagem' => 'Erro ao ao tentar transacionar']
        );
      }
      $this->delete($idTransacao);
      return new ServiceError(['codigo' => ErrorServiceEnum::TransacaoInvalida, 'mensagem' => 'Pagador Sem Saldo']
      );
    }
    return $transacaoValida;
  }

  private function delete(?int $idTransacao): bool
  {
    return $idTransacao && $this->repository->delete($idTransacao);
  }

  private function validaTransacao(Usuario $pagador, float $valor): ServiceError|bool
  {
    if ($pagador->getTipoUsuario() !== TipoUsuarioEnum::Comum) {
      return new ServiceError(['codigo' => ErrorServiceEnum::PagadorInvalido, 'mensagem' => 'Pagador Invalido']);
    }
    if (!$this->autorizacaoService->autoriza()) {
      return new ServiceError(['codigo' => ErrorServiceEnum::NaoAutorizado, 'mensagem' => 'Transacao não Autorizada']
      );
    }
    if (!$this->usuarioService->validarSaldoPagador($pagador, $valor)) {
      return new ServiceError(['codigo' => ErrorServiceEnum::PagadorSemSaldo, 'mensagem' => 'Pagador Sem Saldo']);
    }
    return true;
  }

  private function enviaNotificacao(Usuario $recebedor, float $valor): void
  {
    $this->notificaService->transacaoRecebida($recebedor, $valor);
  }
}