<?php
namespace Controllers;

use Psr\Container\ContainerInterface;

class RepoController{
  protected $ci;

  public function __construct(ContainerInterface $ci){
      $this->ci = $ci;
  }

  public function __invoke($request, $response, $args) {
      $this->ci->logger->info("Slim-Skeleton '/' route");
      $package_name = $args['package-name'];
      $data = $this->ci->APIHandler->repos($package_name);
      $newResponse = $response->withJson($data);
      return $newResponse;
  }
}
?>
