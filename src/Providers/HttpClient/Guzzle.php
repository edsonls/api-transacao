<?php

namespace App\Providers\HttpClient;

use GuzzleHttp\Client;

abstract class Guzzle
{
  private Client $client;

  public function getClient()
  {
    if (empty($this->client)) {
      $this->client = new Client(
        [
          'timeout' => 2.0,
        ]
      );
    }
    return $this->client;
  }
}