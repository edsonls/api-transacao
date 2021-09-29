<?php

require __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use App\Controllers\TransacaoController;
use App\Controllers\UsuarioController;
use App\Utils\Errors\Interfaces\IError;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();
//Usuario
$app->post(
  '/usuario',
  function (Request $request, Response $response) {
    $resp = $response
      ->withHeader('Content-Type', 'application/json');
    $usuarioController = new UsuarioController();
    $resposta = $usuarioController->add($request->getBody());
    $resp->getBody()->write(formataCreate($resposta));
    return $resp->withStatus($resposta->getCodigo());
  }
);
//transacao
$app->post(
  '/transacao',
  function (Request $request, Response $response) {
    $resp = $response
      ->withHeader('Content-Type', 'application/json');
    $transacaoController = new TransacaoController();
    $resposta = $transacaoController->add($request->getBody());
    $resp->getBody()->write(formataCreate($resposta));
    return $resp->withStatus($resposta->getCodigo());
  }
);


$app->run();

function formataCreate(int|IError $resposta): string
{
  if (is_int($resposta)) {
    return json_encode(['id' => $resposta]);
  }
  return json_encode($resposta->getPilhaErro());
}