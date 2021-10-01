<?php

namespace App\Providers\DataBase;

use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use SleekDB\Store;

abstract class SleekDB
{
  private string $databaseDirectory = "/app/dataBase";
  public string $table;
  private Store $connection;
  private array $configuration = [
    "auto_cache" => true,
    "cache_lifetime" => null,
    "timeout" => false, // deprecated! Set it to false!
    "primary_key" => "_id",
  ];

  /**
   * @throws InvalidConfigurationException
   * @throws IOException
   * @throws InvalidArgumentException
   */
  public function getConnection(): Store
  {
    if (empty($this->connection)) {
      return $this->connection = new Store($this->table, $this->databaseDirectory, $this->configuration);
    }
    return $this->connection;
  }
}