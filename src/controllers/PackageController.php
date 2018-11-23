<?php
namespace Controllers;

use Psr\Container\ContainerInterface;
use \Models\Repo;

class PackageController{
  protected $ci;

  public function __construct(ContainerInterface $ci){
      $this->ci = $ci;
  }

  public function __invoke($request, $response, $args) {
      // $post_data = $request->getParsedBody();
      // $data = $this->ci->APIHandler->get_package_file($post_data['url']);

      // $newResponse = $response->withJson($data);
      // return $newResponse;

     $package = new Repo();
     $package->name = "abc";
     $package->full_name = "Def";
     $package->repo_id= "dfsdfsd";
     $package->save();
  }
}
