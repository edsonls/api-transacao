<?php

namespace App\Services;

use App\Entities\Usuario;
use App\Repositories\Interfaces\IUsuarioRepository;
use App\Services\Enum\ErrorServiceEnum;
use App\Services\Interfaces\IUsuarioService;
use App\Utils\Errors\ServiceError;

class UsuarioService implements IUsuarioService
{

  public function __construct(
    private IUsuarioRepository $repository
  ) {
  }

  public function add(array $usuario): ServiceError|int
  {
    if ($this->repository->exists($usuario['documento'], $usuario['email'])) {
      return new ServiceError(
        ['codigo' => ErrorServiceEnum::UsuarioJaCadastrado, "mensagem" => "Usuário já está cadastrado no sistema!"]
      );
    }
    return $this->repository->add(
      new Usuario(
        $usuario['nome'],
        $usuario['documento'],
        $usuario['email'],
        $usuario['senha'],
        $usuario['tipoUsuario'],
        $usuario['saldo'],
      )
    );
  }

  public function find(int $id): ServiceError|Usuario
  {
    return $this->repository->find($id) ?? new ServiceError(
        ['codigo' => ErrorServiceEnum::UsuarioNaoEncontrado, "mensagem" => "Usuario não encontrado no sistema!"]
      );
  }

  public function exists(string $documento, string $email): bool
  {
    return $this->repository->exists($documento, $email);
  }

  public function retiraSaldo(Usuario $pagador, float $valor): bool
  {
    $pagador->atualizaSaldo($pagador->getSaldo() - $valor);
    return $this->repository->update($pagador);
  }

  public function adicionaSaldo(Usuario $recebedor, float $valor): bool
  {
    $recebedor->atualizaSaldo($recebedor->getSaldo() + $valor);
    return $this->repository->update($recebedor);
  }

  public function estornarSaldo(Usuario $pagador, float $valor): bool
  {
    return $this->adicionaSaldo($pagador, $valor);
  }

  public function validarSaldoPagador(Usuario $pagador, float $valor): bool
  {
    return $pagador->getSaldo() >= $valor;
  }
}