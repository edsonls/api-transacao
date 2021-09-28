<?php


use App\Entities\Enum\TipoUsuarioEnum;
use App\Repositories\Guzzle\AutorizacaoRepository;
use App\Repositories\Sleekdb\TransacaoRepository;
use App\Repositories\Sleekdb\UsuarioRepository;
use App\Services\AutorizacaoService;
use App\Services\TransacaoService;
use App\Services\UsuarioService;
use App\Utils\Errors\ServiceError;

beforeEach(
  function () {
    $this->fake = Faker\Factory::create('pt_BR');
    $this->usuarioService = new UsuarioService(new UsuarioRepository());
    $this->transacaoService = new TransacaoService(
      new TransacaoRepository(),
      $this->usuarioService,
      new AutorizacaoService(new AutorizacaoRepository())
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
    $this->lojistaId = $this->usuarioService->add(
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
  'OK - Transacao Usuario -> Lojista',
  function () {
    $pagador = $this->usuarioService->find($this->pagadorId);
    $recebedor = $this->usuarioService->find($this->lojistaId);
    $valor = 50.85;
    expect($this->transacaoService->add($pagador, $recebedor, $valor))
      ->toBeGreaterThanOrEqual(1);
  }
);
it(
  'OK - Transacao Usuario -> Lojista Validando Saldo Pagador',
  function () {
    $pagador = $this->usuarioService->find($this->pagadorId);
    $saldoAnteriorPagador = $pagador->getSaldo();
    $recebedor = $this->usuarioService->find($this->lojistaId);
    $valor = 50.85;
    expect($this->transacaoService->add($pagador, $recebedor, $valor))
      ->toBeGreaterThanOrEqual(1);
    expect($this->usuarioService->find($this->pagadorId)->getSaldo())
      ->toEqual($saldoAnteriorPagador - $valor);
  }
);

it(
  'OK - Transacao Usuario -> Lojista Validando Saldo Recebedor',
  function () {
    $pagador = $this->usuarioService->find($this->pagadorId);
    $recebedor = $this->usuarioService->find($this->lojistaId);
    $saldoAnteriorRecebedor = $pagador->getSaldo();
    $valor = 50.85;
    expect($this->transacaoService->add($pagador, $recebedor, $valor))
      ->toBeGreaterThanOrEqual(1);
    expect($this->usuarioService->find($this->lojistaId)->getSaldo())
      ->toEqual($saldoAnteriorRecebedor + $valor);
  }
);

it(
  'Fail - Transacao Lojista -> Usuario',
  function () {
    $pagadorLojista = $this->usuarioService->add(
      [
        'nome' => $this->fake->name,
        'documento' => $this->fake->cpf,
        'email' => $this->fake->email,
        'senha' => $this->fake->password,
        'tipoUsuario' => TipoUsuarioEnum::Logista,
        'saldo' => $this->fake->randomFloat(2, 100, 100),
      ]
    );
    $pagador = $this->usuarioService->find($pagadorLojista);
    $recebedor = $this->usuarioService->find($this->recebedorId);
    $valor = 50.85;
    expect($this->transacaoService->add($pagador, $recebedor, $valor))
      ->toBeInstanceOf(ServiceError::class);
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
      ->toBeInstanceOf(ServiceError::class);
  }
);

it(
  'Fail - Transacao Usuario -> Lojista Sem Saldo',
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
    $recebedor = $this->usuarioService->find($this->lojistaId);
    $valor = 89;
    expect($this->transacaoService->add($pagador, $recebedor, $valor))
      ->toBeInstanceOf(ServiceError::class);
  }
);

it(
  'Fail - Transacao Usuario -> Lojista Sem Saldo Validando Saldo Pagador',
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
    $recebedor = $this->usuarioService->find($this->lojistaId);
    $valor = 89;
    expect($this->transacaoService->add($pagador, $recebedor, $valor))
      ->toBeInstanceOf(ServiceError::class);
    expect($this->usuarioService->find($pagadorId)->getSaldo())
      ->toEqual($saldoAnteriorPagador);
  }
);

it(
  'Fail - Transacao Usuario -> Lojista Sem Saldo Validando Saldo Recebedor',
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
    $recebedor = $this->usuarioService->find($this->lojistaId);
    $saldoAnteriorRecebedor = $recebedor->getSaldo();
    $valor = 89;
    expect($this->transacaoService->add($pagador, $recebedor, $valor))
      ->toBeInstanceOf(ServiceError::class);
    expect($this->usuarioService->find($this->lojistaId)->getSaldo())
      ->toEqual($saldoAnteriorRecebedor);
  }
);