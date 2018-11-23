<?php
namespace Handlers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Container\ContainerInterface;

class APIHandler
{
  protected $ci;
  public static $git_search_endpoint = 'https://api.github.com/search/repositories';

  public function __construct(ContainerInterface $ci){
      $this->ci = $ci;
  }

  //fetches repos based and keyword
  public function repos(string $keyword) {
      $client = new Client();
      $params = array('q' => $keyword.' in:name ',
                      'sort' => 'stars',
                      'order' => 'desc');
      $res = $client->get(self::$git_search_endpoint, ['query' => $params]);
      if ($res->getStatusCode() == 200){
        $response_json = json_decode($res->getBody());
        return $this->ci->ToppackFormatter->response($response_json);
      }
  }

  // check if there is a package.json and fetch it
  public function get_package_file(string $url) {
      $content_url = 'https://api.github.com/repos/'.str_replace('https://github.com/', '', $url).'/contents/package.json';

      $client = new Client();

      // check for package.json
      try{
        $res = $client->get($content_url);
        $json_response = json_decode($res->getBody());
        if (isset($json_response->message)){
          return ['error' => 'There is no package.json file in the project.'];
        }else{
          $package_json_url = $json_response->download_url;
        }
      }catch (ClientException $e){
        return ['error' => 'There is an error connecting to Github.'];
      }

      // get package.json
      try{
        $res = $client->get($package_json_url);
      }catch (ClientException $e){
        return ['error' => 'There is an error connecting to Github.'];
      }

      // parse package.json
      if ($res->getStatusCode() == 200){
        $response_json = json_decode($res->getBody());
        $repository = $this->ci->ToppackFormatter->dependencies($response_json);
        $repository['url'] = $url;
        return $repository;
      }else{
        return ['error' => 'There is an issue accessing Github.'];
      }
  }
}
?>
