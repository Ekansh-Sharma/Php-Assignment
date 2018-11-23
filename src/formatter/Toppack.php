<?php
namespace Formatter;

class Toppack
{
    public function response($response_json){
      $response = [];
      foreach($response_json->items as $item){
        $row = [
          'updated_at' => $item->updated_at,
          'description' => $item->description,
          'forks_count' => $item->forks_count,
          'html_url' => $item->html_url,
          'name' => $item->name,
          'stargazers_count' => $item->stargazers_count,
          'watchers_count' => $item->watchers_count,
          'owner' => $item->owner->login
        ];
        array_push($response, $row);
      }
      return $response;
    }

    public function dependencies($response_json){
      if(!isset($response_json->devDependencies) && !isset($response_json->dependencies)){
        return array('error' => 'The package.json has no dependencies.');
      }
      $repository = ['name' => $response_json->name];
      $dependencies = [];
      if(isset($response_json->devDependencies)){
        foreach($response_json->devDependencies as $dep => $versions){
          array_push($dependencies, $dep);
        }
      }
      if(isset($response_json->dependencies)) {
        foreach($response_json->dependencies as $dep => $versions){
          array_push($dependencies, $dep);
        }
      }
      $repository['dependencies'] = $dependencies;
      return $repository;
    }
}
