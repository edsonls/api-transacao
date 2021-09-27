<?php

namespace App\Services;

use App\Entities\Usuario;
use App\Repositories\Interfaces\IUsuarioRepository;
use App\Services\Interfaces\IUsuarioService;

class UsuarioService implements IUsuarioService
{


  public function __construct(
    private IUsuarioRepository $repository
  ) {
  }

  public function add(array $usuario): int
  {
    $usuarioObj = new Usuario(
      $usuario['nome'],
      $usuario['documento'],
      $usuario['email'],
      $usuario['senha'],
      $usuario['tipoUsuario'],
      $usuario['saldo'],
    );
    return $this->repository->add($usuarioObj);
  }

  public function find(int $id): Usuario
  {
    return $this->repository->find($id);
  }

  public function retiraSaldo(Usuario $pagador, float $valor): bool
  {
    if ($pagador->getSaldo() <= $valor) {
      return false;
    }
    $pagador->atualizaSaldo($pagador->getSaldo() - $valor);
    return $this->repository->update($pagador);
  }

  public function adicionaSaldo(Usuario $recebedor, float $valor): bool
  {
    $recebedor->atualizaSaldo($recebedor->getSaldo() + $valor);
    return $this->repository->update($recebedor);
  }
}