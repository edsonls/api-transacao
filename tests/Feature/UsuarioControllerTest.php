<?php


use App\Controllers\UsuarioController;
use App\Entities\Enum\TipoUsuarioEnum;
use App\Utils\Errors\Interfaces\IError;
use GuzzleHttp\Psr7\Utils;

beforeEach(
  function () {
    $this->fake = Faker\Factory::create('pt_BR');
    $this->usuarioController = new UsuarioController();
  }
);
it(
  'OK - Criando Usuario Comum',
  function () {
    expect(
      $this->usuarioController->add(
        Utils::streamFor(
          json_encode([
                        'nome' => $this->fake->name,
                        'documento' => $this->fake->cpf(false),
                        'email' => $this->fake->email,
                        'senha' => $this->fake->password,
                        'tipoUsuario' => TipoUsuarioEnum::Comum,
                        'saldo' => 50,
                      ])
        )
      )
    )
      ->toBeInt();
  }
);
it(
  'OK - Criando Usuario Logista',
  function () {
    expect(
      $this->usuarioController->add(
        Utils::streamFor(
          json_encode([
                        'nome' => $this->fake->name,
                        'documento' => $this->fake->cpf(false),
                        'email' => $this->fake->email,
                        'senha' => $this->fake->password,
                        'tipoUsuario' => TipoUsuarioEnum::Logista,
                        'saldo' => 50,
                      ])
        )
      )
    )
      ->toBeInt();
  }
);

it(
  'OK - Criando Usuario Comum Sem saldo',
  function () {
    expect(
      $this->usuarioController->add(
        Utils::streamFor(
          json_encode([
                        'nome' => $this->fake->name,
                        'documento' => $this->fake->cpf(false),
                        'email' => $this->fake->email,
                        'senha' => $this->fake->password,
                        'tipoUsuario' => TipoUsuarioEnum::Comum,
                        'saldo' => 0,
                      ])
        )
      )
    )
      ->toBeInt();
  }
);

it(
  'OK - Criando Usuario Logista Sem saldo',
  function () {
    expect(
      $this->usuarioController->add(
        Utils::streamFor(
          json_encode([
                        'nome' => $this->fake->name,
                        'documento' => $this->fake->cpf(false),
                        'email' => $this->fake->email,
                        'senha' => $this->fake->password,
                        'tipoUsuario' => TipoUsuarioEnum::Logista,
                        'saldo' => 0,
                      ])
        )
      )
    )
      ->toBeInt();
  }
);

it(
  'Fail - Criando Usuario Comum Documento Repetido',
  function () {
    $documento = $this->fake->cpf(false);
    $this->usuarioController->add(
      Utils::streamFor(
        json_encode([
                      'nome' => $this->fake->name,
                      'documento' => $documento,
                      'email' => $this->fake->email,
                      'senha' => $this->fake->password,
                      'tipoUsuario' => TipoUsuarioEnum::Comum,
                      'saldo' => 0,
                    ])
      )
    );
    expect(
      $this->usuarioController->add(
        Utils::streamFor(
          json_encode([
                        'nome' => $this->fake->name,
                        'documento' => $documento,
                        'email' => $this->fake->email,
                        'senha' => $this->fake->password,
                        'tipoUsuario' => TipoUsuarioEnum::Comum,
                        'saldo' => 0,
                      ])
        )
      )
    )
      ->toBeInstanceOf(IError::class);
  }
);
it(
  'Fail - Criando Usuario Logista Documento Repetido',
  function () {
    $documento = $this->fake->cpf(false);
    $this->usuarioController->add(
      Utils::streamFor(
        json_encode([
                      'nome' => $this->fake->name,
                      'documento' => $documento,
                      'email' => $this->fake->email,
                      'senha' => $this->fake->password,
                      'tipoUsuario' => TipoUsuarioEnum::Logista,
                      'saldo' => 0,
                    ])
      )
    );
    expect(
      $this->usuarioController->add(
        Utils::streamFor(
          json_encode([
                        'nome' => $this->fake->name,
                        'documento' => $documento,
                        'email' => $this->fake->email,
                        'senha' => $this->fake->password,
                        'tipoUsuario' => TipoUsuarioEnum::Logista,
                        'saldo' => 0,
                      ])
        )
      )
    )
      ->toBeInstanceOf(IError::class);
  }
);