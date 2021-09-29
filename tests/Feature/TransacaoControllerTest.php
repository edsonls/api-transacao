<?php


use App\Controllers\TransacaoController;
use App\Entities\Enum\TipoUsuarioEnum;
use App\Repositories\Sleekdb\UsuarioRepository;
use App\Services\UsuarioService;
use App\Utils\Errors\Interfaces\IError;
use GuzzleHttp\Psr7\Utils;

beforeEach(
  function () {
    $this->fake = Faker\Factory::create('pt_BR');
    $this->transacaoController = new TransacaoController();
    $this->usuarioService = new UsuarioService(new UsuarioRepository());
    $this->pagadorId = $this->usuarioService->add(
      [
        'nome' => $this->fake->name,
        'documento' => $this->fake->cpf(false),
        'email' => $this->fake->email,
        'senha' => $this->fake->password,
        'tipoUsuario' => TipoUsuarioEnum::Comum,
        'saldo' => $this->fake->randomFloat(2, 100, 100),
      ]
    );
    $this->recebedorId = $this->usuarioService->add(
      [
        'nome' => $this->fake->name,
        'documento' => $this->fake->cpf(false),
        'email' => $this->fake->email,
        'senha' => $this->fake->password,
        'tipoUsuario' => TipoUsuarioEnum::Comum,
        'saldo' => $this->fake->randomFloat(2, 100, 100),
      ]
    );
    $this->logistaId = $this->usuarioService->add(
      [
        'nome' => $this->fake->name,
        'documento' => $this->fake->cpf(false),
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
    expect(
      $this->transacaoController->add(
        Utils::streamFor(
          json_encode([
                        'pagador' => $this->pagadorId,
                        'recebedor' => $this->recebedorId,
                        'valor' => 50.85,
                      ])
        )
      )
    )
      ->toBeInt();
  }
);
it(
  'OK - Transacao Usuario -> Logista',
  function () {
    expect(
      $this->transacaoController->add(
        Utils::streamFor(
          json_encode([
                        'pagador' => $this->pagadorId,
                        'recebedor' => $this->logistaId,
                        'valor' => 50.85,
                      ])
        )
      )
    )
      ->toBeInt();
  }
);

it(
  'Fail - Transacao Logista -> Usuario',
  function () {
    expect(
      $this->transacaoController->add(
        Utils::streamFor(
          json_encode([
                        'pagador' => $this->logistaId,
                        'recebedor' => $this->pagadorId,
                        'valor' => 50.85,
                      ])
        )
      )
    )
      ->toBeInstanceOf(IError::class);
  }
);
it(
  'Fail - Transacao Logista -> Logista',
  function () {
    expect(
      $this->transacaoController->add(
        Utils::streamFor(
          json_encode([
                        'pagador' => $this->logistaId,
                        'recebedor' => $this->logistaId,
                        'valor' => 50.85,
                      ])
        )
      )
    )
      ->toBeInstanceOf(IError::class);
  }
);
it(
  'Fail - Transacao Usuario -> Logista Valor negativo',
  function () {
    expect(
      $this->transacaoController->add(
        Utils::streamFor(
          json_encode([
                        'pagador' => $this->pagadorId,
                        'recebedor' => $this->logistaId,
                        'valor' => -50.85,
                      ])
        )
      )
    )
      ->toBeInstanceOf(IError::class);
  }
);
it(
  'Fail - Transacao Usuario invalido -> Logista',
  function () {
    expect(
      $this->transacaoController->add(
        Utils::streamFor(
          json_encode([
                        'pagador' => 'abc',
                        'recebedor' => $this->logistaId,
                        'valor' => 50.85,
                      ])
        )
      )
    )
      ->toBeInstanceOf(IError::class);
  }
);
it(
  'Fail - Transacao Usuario -> Logista invalido',
  function () {
    expect(
      $this->transacaoController->add(
        Utils::streamFor(
          json_encode([
                        'pagador' => $this->recebedorId,
                        'recebedor' => 'asdasd',
                        'valor' => 50.85,
                      ])
        )
      )
    )
      ->toBeInstanceOf(IError::class);
  }
);
it(
  'Fail - Transacao Usuario invalido -> Logista invalido',
  function () {
    expect(
      $this->transacaoController->add(
        Utils::streamFor(
          json_encode([
                        'pagador' => 'sadasdasdas',
                        'recebedor' => 'asdasd',
                        'valor' => 50.85,
                      ])
        )
      )
    )
      ->toBeInstanceOf(IError::class);
  }
);