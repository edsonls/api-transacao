<?php


use App\Entities\Enum\TipoUsuarioEnum;
use App\Repositories\Sleekdb\TransacaoRepository;
use App\Repositories\Sleekdb\UsuarioRepository;
use App\Services\TransacaoService;
use App\Services\UsuarioService;

beforeEach(
  function () {
    $this->fake = Faker\Factory::create('pt_BR');
    $this->usuarioService = new UsuarioService(new UsuarioRepository());
    $this->transacaoService = new TransacaoService(new TransacaoRepository(), $this->usuarioService);
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
  }
);
it(
  'Criando Transacao e validando saldo do pagador e recebedor',
  function () {
    $pagador = $this->usuarioService->find($this->pagadorId);
    $saldoAnteriorPagador = $pagador->getSaldo();
    $recebedor = $this->usuarioService->find($this->recebedorId);
    $saldoAnteriorRecebedor = $pagador->getSaldo();
    $valor = 50.85;
    expect($this->transacaoService->add($pagador, $recebedor, $valor))
      ->toBeGreaterThanOrEqual(1);
    expect($this->usuarioService->find($this->pagadorId)->getSaldo())
      ->toEqual($saldoAnteriorPagador - $valor);
    expect($this->usuarioService->find($this->recebedorId)->getSaldo())
      ->toEqual($saldoAnteriorRecebedor + $valor);
  }
);