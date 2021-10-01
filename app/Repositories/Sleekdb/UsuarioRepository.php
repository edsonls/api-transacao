<?php

namespace App\Repositories\Sleekdb;

use App\Entities\Usuario;
use App\Providers\DataBase\SleekDB;
use App\Repositories\Interfaces\IUsuarioRepository;
use App\Utils\Log\AppLog;
use Exception;

class UsuarioRepository extends SleekDB implements IUsuarioRepository
{

  public function __construct()
  {
    $this->table = 'usuario';
  }

  public function add(Usuario $usuario): ?int
  {
    try {
      return $this->getConnection()->insert(
        [
          'nome' => $usuario->getNome(),
          'documento' => $usuario->getDocumento(),
          'email' => $usuario->getEmail(),
          'senha' => $usuario->getSenha(),
          'saldo' => $usuario->getSaldo(),
          'tipoUsuario' => $usuario->getTipoUsuario(),
        ]
      )
      ['_id'];
    } catch (Exception $exception) {
      AppLog::error('UsuarioRepository', $exception->getMessage());
      return null;
    }
  }

  public function find(int $id): ?Usuario
  {
    try {
      $arrayUsuario = $this->getConnection()->findById($id);
      if ($arrayUsuario === null) {
        return null;
      }
      return new Usuario(
                     $arrayUsuario['nome'],
                     $arrayUsuario['documento'],
                     $arrayUsuario['email'],
        senha:       '',
        tipoUsuario: $arrayUsuario['tipoUsuario'],
        saldo:       $arrayUsuario['saldo'],
        id:          $arrayUsuario['_id'],
      );
    } catch (Exception $exception) {
      AppLog::error('UsuarioRepository', $exception->getMessage());
      return null;
    }
  }

  public function update(Usuario $usuario): bool
  {
    try {
      return $this->getConnection()->updateById(
          $usuario->getId(),
          [
            'nome' => $usuario->getNome(),
            'documento' => $usuario->getDocumento(),
            'email' => $usuario->getEmail(),
            'senha' => $usuario->getSenha(),
            'saldo' => $usuario->getSaldo(),
            'tipoUsuario' => $usuario->getTipoUsuario(),
          ]
        ) !== false;
    } catch (Exception $exception) {
      AppLog::error('UsuarioRepository', $exception->getMessage());
      return false;
    }
  }

  public function exists(string $documento, string $email): bool
  {
    try {
      return $this->getConnection()->findOneBy([["documento", "=", $documento], "OR", ["email", "=", $email]]) !== null;
    } catch (Exception $exception) {
      AppLog::error('UsuarioRepository', $exception->getMessage());
      return false;
    }
  }
}