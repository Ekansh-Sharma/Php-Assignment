<?php

namespace Models;
use \Models\ModelPDO;
use \PDO;

class Repo extends ModelPDO {
    public function __construct($data = false) {
        $schema = array(
        	'repo_id' => PDO::PARAM_INT,
            'name' => PDO::PARAM_STR,
            'full_name' => PDO::PARAM_STR,
            'url' => PDO::PARAM_STR,
            'stargazers_count' => PDO::PARAM_INT,
            'watchers_count' => PDO::PARAM_INT,
            'forks_count' => PDO::PARAM_INT
        );
        parent::__construct($schema, $data);
    }
}
?>