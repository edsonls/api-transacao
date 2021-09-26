<?php

namespace App\Repositories\Interfaces;

use App\Entities\Usuario;

interface IUsuarioRepository
{
  function add(Usuario $usuario): int;
}