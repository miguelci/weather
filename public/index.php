<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Weather\Controller\PredictionController;
use Weather\Temperature;

$request = Request::createFromGlobals();

$controller = new PredictionController();

switch ($request->getPathInfo()) {
    case '/':
        return new Response((string) require_once 'views/index.html');
    case '/predictions':

        $date = $request->query->get('date', '2018-01-12');
        $scale = $request->query->get('scale', Temperature::DEFAULT_SCALE);

        $response = $controller->index($date, $scale);
        break;
    case '/fixtures':
        $response = $controller->fixtures();
        break;
    default:
        return new Response('no route');
}

$response->send();
