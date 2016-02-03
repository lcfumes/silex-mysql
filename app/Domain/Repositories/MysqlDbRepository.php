<?php

namespace app\Domain\Repositories;

use app\Domain\Entities\ClientEntity;

class MysqlDbRepository extends AbstractMysqlDbRepository
{
    public function __construct($config)
    {
        parent::__construct($config);
    }

    /**
     * @param ClientEntity $client [description]
     *
     * @return bool
     */
    public function save(ClientEntity $client)
    {
        if (count($client->toArray()) === 0) {
            return false;
        }

        return $this->dbConnection['mysql_read']->insert('users', $client->toArray());
    }

    /**
     * @param ClientEntity $client [description]
     *
     * @return bool || array
     */
    public function search(ClientEntity $client)
    {
        $search = [];
        if (strlen($client->getFirstName()) > 0) {
            $search['first_name'] = $client->getFirstName();
        }
        if (strlen($client->getLastName()) > 0) {
            $search['last_name'] = $client->getLastName();
        }
        if (strlen($client->getEmail()) > 0) {
            $search['email'] = $client->getEmail();
        }
        if (strlen($client->getAge()) > 0) {
            $search['age'] = $client->getAge();
        }

        if (count($search) === 0) {
            return false;
        }

        $conditional = '';
        foreach ($search as $key => $value) {
            $conditional .= $key.'='."'".$value."'".' AND ';
        }
        $conditional = substr($conditional, 0, -5);

        return $this->dbConnection['mysql_read']->fetchAll('SELECT id, first_name, last_name, email, age FROM users WHERE '.$conditional.' ORDER BY first_name');
    }
}
