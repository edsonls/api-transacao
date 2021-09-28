<?php

namespace App\Controllers;

use App\Repositories\Guzzle\AutorizacaoRepository;
use App\Repositories\Sleekdb\TransacaoRepository;
use App\Repositories\Sleekdb\UsuarioRepository;
use App\Services\AutorizacaoService;
use App\Services\Interfaces\ITransacaoService;
use App\Services\Interfaces\IUsuarioService;
use App\Services\TransacaoService;
use App\Services\UsuarioService;
use JetBrains\PhpStorm\Pure;
use JsonException;
use Psr\Http\Message\StreamInterface;

class TransacaoController
{

  private ITransacaoService $service;
  private IUsuarioService $usuarioService;

  #[Pure]
  public function __construct()
  {
    $this->usuarioService = new UsuarioService(new UsuarioRepository());
    $this->service = new TransacaoService(
      new TransacaoRepository(),
      $this->usuarioService,
      new AutorizacaoService(new AutorizacaoRepository())
    );
  }

  /**
   * @throws JsonException
   */
  public function add(StreamInterface $request): int
  {
    $body = json_decode($request->__toString(), true, 512, JSON_THROW_ON_ERROR);
    $pagador = $this->usuarioService->find($body['pagador']);
    $recebedor = $this->usuarioService->find($body['recebedor']);
    return $this->service->add($pagador, $recebedor, $body['valor']);
  }
}