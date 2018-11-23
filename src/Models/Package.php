<?php

namespace Models;
use \Models\ModelPDO;
use \PDO;

class Package extends ModelPDO {
    public function __construct($data = false) {
        $schema = array(
            'name' => PDO::PARAM_STR,
            'version' => PDO::PARAM_STR
        );
        parent::__construct($schema, $data);
    }
}
?>
