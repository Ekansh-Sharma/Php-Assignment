<?php
use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/packages/{package-name}', RepoController::class);
$app->post('/package/import/', PackageController::class);
$app->get('/trial', PackageController::class);
