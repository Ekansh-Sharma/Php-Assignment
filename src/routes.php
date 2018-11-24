<?php
use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', function (Request $request, Response $response, array $args) {
    return $this->renderer->render($response, "/index.html");
});
$app->get('/packages/{package-name}', RepoController::class);
$app->post('/package/import/', PackageController::class);
$app->get('/trial', PackageController::class);
