<?php

namespace App\Controllers;

use App\Repositories\Sleekdb\UsuarioRepository;
use App\Services\Interfaces\IUsuarioService;
use App\Services\UsuarioService;
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
  public function add(StreamInterface $request): int
  {
    $body = json_decode($request->__toString(), true, 512, JSON_THROW_ON_ERROR);
    return $this->service->add($body);
  }
}