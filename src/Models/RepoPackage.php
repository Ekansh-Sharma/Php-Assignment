<?php
class RepoPackage extends ModelPDO {
    public function __construct($data = false) {
        $schema = array(
            'repo_id' => PDO::PARAM_INT,
            'package_id' => PDO::PARAM_INT
        );
        parent::__construct($schema, $data);
    }
}
?>