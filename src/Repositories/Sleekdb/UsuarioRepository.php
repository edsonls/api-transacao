<?php

namespace App\Repositories\Sleekdb;

use App\Entities\Usuario;
use App\Providers\DataBase\SleekDB;
use App\Repositories\Interfaces\IUsuarioRepository;
use SleekDB\Exceptions\IdNotAllowedException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use SleekDB\Exceptions\JsonException;

class UsuarioRepository extends SleekDB implements IUsuarioRepository
{

  public function __construct()
  {
    $this->table = 'usuario';
  }

  /**
   * @throws IOException
   * @throws JsonException
   * @throws InvalidArgumentException
   * @throws IdNotAllowedException
   * @throws InvalidConfigurationException
   */
  public function add(Usuario $usuario): int
  {
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
  }

  /**
   * @throws IOException
   * @throws InvalidConfigurationException
   * @throws InvalidArgumentException
   */
  public function find(int $id): ?Usuario
  {
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
  }

  public function update(Usuario $usuario): bool
  {
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
  }

  public function exists(string $documento, string $email): bool
  {
    return $this->getConnection()->findOneBy([["documento", "=", $documento], "OR", ["email", "=", $email]]) !== null;
  }
}