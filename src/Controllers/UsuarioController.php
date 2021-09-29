<?php

namespace App\Controllers;

use App\Repositories\Sleekdb\UsuarioRepository;
use App\Services\Interfaces\IUsuarioService;
use App\Services\UsuarioService;
use App\Utils\Errors\ControllerError;
use App\Utils\Validations\RequestValidation;
use JetBrains\PhpStorm\Pure;
use JsonException;
use Psr\Http\Message\StreamInterface;

class UsuarioController
{

  private IUsuarioService $service;

  #[Pure]
  public function __construct()
  {
    $this->service = new UsuarioService(new UsuarioRepository());
  }

  /**
   * @throws JsonException
   */
  public function add(StreamInterface $request): ControllerError|int
  {
    $body = json_decode($request->__toString(), true, 512, JSON_THROW_ON_ERROR);

    $validacao = RequestValidation::validaUsuario($body);

    if ($validacao !== true) {
      return $validacao;
    }

    return $this->service->add($body);
  }
}