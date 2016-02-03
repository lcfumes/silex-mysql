<?php

namespace app\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\ControllerProviderInterface;
use app\Domain\Entities\ClientEntity;
use app\Provider\Form\SearchClientFormProvider;
use app\Domain\Services\MysqlDbService;
use app\Domain\Repositories\MysqlDbRepository;
use app\Provider\Form\CreateClientFormProvider;

class ClientsController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controller = $app['controllers_factory'];

        $controller->get('save', [$this, 'saveAction'])
            ->method('POST')
            ->bind('save.client');

        $controller->get('search', [$this, 'searchAction'])
            ->method('POST')
            ->bind('search.client');

        return $controller;
    }

    public function saveAction(Request $request, Application $app)
    {
        $formCreate = new CreateClientFormProvider($app);

        $form = $formCreate->create();

        $form->handleRequest($request);

        if ($request->getMethod() === 'POST' && $form->isValid()) {
            $data = $request->request->get('form');

            $clientEntity = new ClientEntity();
            $clientEntity->setFirstName($data['first_name']);
            $clientEntity->setLastName($data['last_name']);
            $clientEntity->setEmail($data['email']);
            $clientEntity->setAge($data['age']);

            $mysqlDbService = new MysqlDbService(new MysqlDbRepository($app['dbs']));
            $result = $mysqlDbService->save($clientEntity);

            if ($result) {
                return new Response(
                    json_encode(['result' => true]),
                    200,
                    ['Content-Type' => 'application/json']

                );
            }
        }

        return new Response(
            json_encode(['result' => false]),
            503,
            ['Content-Type' => 'application/json']
        );
    }

    public function searchAction(Request $request, Application $app)
    {
        $formCreate = new SearchClientFormProvider($app);

        $form = $formCreate->create();

        $form->handleRequest($request);
        if ($request->getMethod() === 'POST' && $form->isValid()) {
            $mysqlDbService = new MysqlDbService(new MysqlDbRepository($app['dbs']));

            $data = $request->request->get('form');

            $clientEntity = new ClientEntity();
            $clientEntity->setFirstName($data['first_name']);
            $clientEntity->setLastName($data['last_name']);
            $clientEntity->setEmail($data['email']);
            $clientEntity->setAge($data['age']);

            $search = $mysqlDbService->search($clientEntity);

            return new Response(
                json_encode($search->toArray()),
                200,
                ['Content-Type' => 'application/json']
            );
        }

        return new Response(
            json_encode(['result' => false]),
            503,
            ['Content-Type' => 'application/json']
        );
    }
}
