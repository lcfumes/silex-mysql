<?php

namespace app\Domain\Services;

use app\Domain\Collectors\ClientCollector;
use app\Domain\Entities\ClientEntity;
use app\Domain\Repositories\MysqlDbRepository;

class MysqlDbService
{
    private $repository;

    /**
     * @param MysqlDbRepository
     */
    public function __construct($repository)
    {
        if (!$repository instanceof MysqlDbRepository) {
            throw new \InvalidArgumentException('Expected MysqlDbRepository in MysqlDbService');
        }

        $this->repository = $repository;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param  ClientEntity
     *
     * @return bool
     */
    public function save($client)
    {
        if (!$client instanceof ClientEntity) {
            throw new \InvalidArgumentException('Expected ClientEntity in saveClient');
        }

        return $this->repository->save($client);
    }

    /**
     * @var ClientEntity
     *
     * @return ClientCollector
     */
    public function search($client)
    {
        if (!$client instanceof ClientEntity) {
            throw new \InvalidArgumentException('Expected ClientEntity in saveClient');
        }

        $result = $this->repository->search($client);

        $clientCollector = new ClientCollector();

        if (!$result) {
            return $clientCollector;
        }

        foreach ($result as $users) {
            $clientEntity = new ClientEntity();

            $clientEntity->setId($users['id']);
            $clientEntity->setFirstName($users['first_name']);
            $clientEntity->setLastName($users['last_name']);
            $clientEntity->setEmail($users['email']);
            $clientEntity->setAge($users['age']);

            $clientCollector->add($clientEntity);
        }

        return $clientCollector;
    }
}
