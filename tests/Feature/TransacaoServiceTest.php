<?php


use App\Entities\Enum\TipoUsuarioEnum;
use App\Repositories\Guzzle\AutorizacaoRepository;
use App\Repositories\Guzzle\NotificacaoRepository;
use App\Repositories\Sleekdb\TransacaoRepository;
use App\Repositories\Sleekdb\UsuarioRepository;
use App\Services\AutorizacaoService;
use App\Services\NotificacaoService;
use App\Services\TransacaoService;
use App\Services\UsuarioService;
use App\Utils\Errors\Interfaces\IError;

beforeEach(
  function () {
    $this->fake = Faker\Factory::create('pt_BR');
    $this->usuarioService = new UsuarioService(new UsuarioRepository());
    $this->transacaoService = new TransacaoService(
      new TransacaoRepository(),
      $this->usuarioService,
      new AutorizacaoService(new AutorizacaoRepository()),
      new NotificacaoService(new NotificacaoRepository())
    );
    $this->pagadorId = $this->usuarioService->add(
      [
        'nome' => $this->fake->name,
        'documento' => $this->fake->cpf,
        'email' => $this->fake->email,
        'senha' => $this->fake->password,
        'tipoUsuario' => TipoUsuarioEnum::Comum,
        'saldo' => $this->fake->randomFloat(2, 100, 100),
      ]
    );
    $this->recebedorId = $this->usuarioService->add(
      [
        'nome' => $this->fake->name,
        'documento' => $this->fake->cpf,
        'email' => $this->fake->email,
        'senha' => $this->fake->password,
        'tipoUsuario' => TipoUsuarioEnum::Comum,
        'saldo' => $this->fake->randomFloat(2, 100, 100),
      ]
    );
    $this->logistaId = $this->usuarioService->add(
      [
        'nome' => $this->fake->name,
        'documento' => $this->fake->cpf,
        'email' => $this->fake->email,
        'senha' => $this->fake->password,
        'tipoUsuario' => TipoUsuarioEnum::Logista,
        'saldo' => $this->fake->randomFloat(2, 100, 100),
      ]
    );
  }
);
it(
  'OK - Transacao Usuario -> Usuario',
  function () {
    $pagador = $this->usuarioService->find($this->pagadorId);
    $recebedor = $this->usuarioService->find($this->recebedorId);
    $valor = 50.85;
    expect($this->transacaoService->add($pagador, $recebedor, $valor))
      ->toBeGreaterThanOrEqual(1);
  }
);
it(
  'OK - Transacao Usuario -> Usuario Validando Saldo Pagador',
  function () {
    $pagador = $this->usuarioService->find($this->pagadorId);
    $saldoAnteriorPagador = $pagador->getSaldo();
    $recebedor = $this->usuarioService->find($this->recebedorId);
    $valor = 50.85;
    expect($this->transacaoService->add($pagador, $recebedor, $valor))
      ->toBeGreaterThanOrEqual(1);
    expect($this->usuarioService->find($this->pagadorId)->getSaldo())
      ->toEqual($saldoAnteriorPagador - $valor);
  }
);

it(
  'OK - Transacao Usuario -> Usuario Validando Saldo Recebedor',
  function () {
    $pagador = $this->usuarioService->find($this->pagadorId);
    $recebedor = $this->usuarioService->find($this->recebedorId);
    $saldoAnteriorRecebedor = $pagador->getSaldo();
    $valor = 50.85;
    expect($this->transacaoService->add($pagador, $recebedor, $valor))
      ->toBeGreaterThanOrEqual(1);
    expect($this->usuarioService->find($this->recebedorId)->getSaldo())
      ->toEqual($saldoAnteriorRecebedor + $valor);
  }
);
it(
  'OK - Transacao Usuario -> Logista',
  function () {
    $pagador = $this->usuarioService->find($this->pagadorId);
    $recebedor = $this->usuarioService->find($this->logistaId);
    $valor = 50.85;
    expect($this->transacaoService->add($pagador, $recebedor, $valor))
      ->toBeGreaterThanOrEqual(1);
  }
);
it(
  'OK - Transacao Usuario -> Logista Validando Saldo Pagador',
  function () {
    $pagador = $this->usuarioService->find($this->pagadorId);
    $saldoAnteriorPagador = $pagador->getSaldo();
    $recebedor = $this->usuarioService->find($this->logistaId);
    $valor = 50.85;
    expect($this->transacaoService->add($pagador, $recebedor, $valor))
      ->toBeGreaterThanOrEqual(1);
    expect($this->usuarioService->find($this->pagadorId)->getSaldo())
      ->toEqual($saldoAnteriorPagador - $valor);
  }
);

it(
  'OK - Transacao Usuario -> Logista Validando Saldo Recebedor',
  function () {
    $pagador = $this->usuarioService->find($this->pagadorId);
    $recebedor = $this->usuarioService->find($this->logistaId);
    $saldoAnteriorRecebedor = $pagador->getSaldo();
    $valor = 50.85;
    expect($this->transacaoService->add($pagador, $recebedor, $valor))
      ->toBeGreaterThanOrEqual(1);
    expect($this->usuarioService->find($this->logistaId)->getSaldo())
      ->toEqual($saldoAnteriorRecebedor + $valor);
  }
);

it(
  'Fail - Transacao Logista -> Usuario',
  function () {
    $pagadorlogista = $this->usuarioService->add(
      [
        'nome' => $this->fake->name,
        'documento' => $this->fake->cpf,
        'email' => $this->fake->email,
        'senha' => $this->fake->password,
        'tipoUsuario' => TipoUsuarioEnum::Logista,
        'saldo' => $this->fake->randomFloat(2, 100, 100),
      ]
    );
    $pagador = $this->usuarioService->find($pagadorlogista);
    $recebedor = $this->usuarioService->find($this->recebedorId);
    $valor = 50.85;
    expect($this->transacaoService->add($pagador, $recebedor, $valor))
      ->toBeInstanceOf(IError::class);
  }
);

it(
  'Fail - Transacao Logista -> Logista',
  function () {
    $pagadorlogista = $this->usuarioService->add(
      [
        'nome' => $this->fake->name,
        'documento' => $this->fake->cpf,
        'email' => $this->fake->email,
        'senha' => $this->fake->password,
        'tipoUsuario' => TipoUsuarioEnum::Logista,
        'saldo' => $this->fake->randomFloat(2, 100, 100),
      ]
    );
    $pagador = $this->usuarioService->find($pagadorlogista);
    $recebedor = $this->usuarioService->find($this->recebedorId);
    $valor = 50.85;
    expect($this->transacaoService->add($pagador, $recebedor, $valor))
      ->toBeInstanceOf(IError::class);
  }
);

it(
  'Fail - Transacao Usuario -> Usuario Sem Saldo',
  function () {
    $pagadorId = $this->usuarioService->add(
      [
        'nome' => $this->fake->name,
        'documento' => $this->fake->cpf,
        'email' => $this->fake->email,
        'senha' => $this->fake->password,
        'tipoUsuario' => TipoUsuarioEnum::Comum,
        'saldo' => 50,
      ]
    );
    $pagador = $this->usuarioService->find($pagadorId);
    $recebedor = $this->usuarioService->find($this->recebedorId);
    $valor = 89;
    expect($this->transacaoService->add($pagador, $recebedor, $valor))
      ->toBeInstanceOf(IError::class);
  }
);

it(
  'Fail - Transacao Usuario -> Logista Sem Saldo',
  function () {
    $pagadorId = $this->usuarioService->add(
      [
        'nome' => $this->fake->name,
        'documento' => $this->fake->cpf,
        'email' => $this->fake->email,
        'senha' => $this->fake->password,
        'tipoUsuario' => TipoUsuarioEnum::Comum,
        'saldo' => 50,
      ]
    );
    $pagador = $this->usuarioService->find($pagadorId);
    $recebedor = $this->usuarioService->find($this->logistaId);
    $valor = 89;
    expect($this->transacaoService->add($pagador, $recebedor, $valor))
      ->toBeInstanceOf(IError::class);
  }
);

it(
  'Fail - Transacao Usuario -> Logista Sem Saldo Validando Saldo Pagador',
  function () {
    $pagadorId = $this->usuarioService->add(
      [
        'nome' => $this->fake->name,
        'documento' => $this->fake->cpf,
        'email' => $this->fake->email,
        'senha' => $this->fake->password,
        'tipoUsuario' => TipoUsuarioEnum::Comum,
        'saldo' => 50,
      ]
    );
    $pagador = $this->usuarioService->find($pagadorId);
    $saldoAnteriorPagador = $pagador->getSaldo();
    $recebedor = $this->usuarioService->find($this->logistaId);
    $valor = 89;
    expect($this->transacaoService->add($pagador, $recebedor, $valor))
      ->toBeInstanceOf(IError::class);
    expect($this->usuarioService->find($pagadorId)->getSaldo())
      ->toEqual($saldoAnteriorPagador);
  }
);

it(
  'Fail - Transacao Usuario -> Logista Sem Saldo Validando Saldo Recebedor',
  function () {
    $pagadorId = $this->usuarioService->add(
      [
        'nome' => $this->fake->name,
        'documento' => $this->fake->cpf,
        'email' => $this->fake->email,
        'senha' => $this->fake->password,
        'tipoUsuario' => TipoUsuarioEnum::Comum,
        'saldo' => 50,
      ]
    );
    $pagador = $this->usuarioService->find($pagadorId);
    $recebedor = $this->usuarioService->find($this->logistaId);
    $saldoAnteriorRecebedor = $recebedor->getSaldo();
    $valor = 89;
    expect($this->transacaoService->add($pagador, $recebedor, $valor))
      ->toBeInstanceOf(IError::class);
    expect($this->usuarioService->find($this->logistaId)->getSaldo())
      ->toEqual($saldoAnteriorRecebedor);
  }
);