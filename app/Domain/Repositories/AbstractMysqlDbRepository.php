<?php

namespace app\Domain\Repositories;

use app\Domain\Entities\ClientEntity;

abstract class AbstractMysqlDbRepository
{
    protected $dbConnection;

    public function __construct($dbConnect)
    {
        $this->dbConnection = $dbConnect;
    }

    abstract public function save(ClientEntity $client);

    abstract public function search(ClientEntity $client);
}
