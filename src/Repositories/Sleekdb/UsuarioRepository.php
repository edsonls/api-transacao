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
      ]
    )
    ['_id'];
  }
}