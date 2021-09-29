<?php

namespace App\Utils\Validations;

use App\Utils\Errors\ControllerError;
use Rakit\Validation\Validator;

abstract class RequestValidation
{
  private const regras = [
    "transacao" => [
      'valor' => 'required|integer',
      'pagador' => 'required|integer',
      'recebedor' => 'required|integer',
    ],
    "usuario" => [
      'nome' => 'required|min:5',
      'documento' => 'required|min:11|max:11',
      'email' => 'required|email',
      'senha' => 'required|min:6',
      'saldo' => 'required|numeric',
      'tipoUsuario' => 'required|integer',
    ],
  ];


  private const i18n = [
    "pt-br" => [
      'required' => 'Campo obrigatório.',
      'integer' => 'Valor tem que ser do tipo inteiro.',
      'nome:min' => 'Campo nome tem que ter no minímo 5 caracteres',
      'documento:min' => 'Campo documento tem que ter 11 números.',
      'documento:max' => 'Campo documento tem que ter 11 números.',
      'senha:min' => 'Campo senha tem que ter no minímo 6 caracteres',
      'numeric' => 'Campo tem que ser do tipo númerico',

    ],
  ];

  public static function validaTransacao(array $request): bool|ControllerError
  {
    $validator = new Validator;

    $validation = $validator->make($request, self::regras["transacao"]);

    $validation->setMessages(self::i18n["pt-br"]);

    $validation->validate();

    return $validation->fails() ? new ControllerError($validation->errors()->firstOfAll()) : true;
  }

  public static function validaUsuario(array $request): bool|ControllerError
  {
    $validator = new Validator;

    $validation = $validator->make($request, self::regras["usuario"]);

    $validation->setMessages(self::i18n["pt-br"]);

    $validation->validate();

    return $validation->fails() ? new ControllerError($validation->errors()->firstOfAll()) : true;
  }

}