<?php

namespace Api;

require $GLOBALS['Boot']->config->getConfigValue(array('dbmodel', 'path')) . "TestModel.php";

class IndexController {
    private $DefaultController = true;
    private $DefaultAction = "index";

    public function indexAction() {
        $test = new TestModel();
        $test->find(array('Id' => 2));

        while ($test->next()) {
            exit(json_encode(print_r($test, true), JSON_PRETTY_PRINT));
        }
    }

    public function bluppAction() {

    }
}
