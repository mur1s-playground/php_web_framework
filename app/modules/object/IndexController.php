<?php

namespace Api;

require $GLOBALS['Boot']->config->getConfigValue(array('dbmodel', 'parentpath')) . "Join.php";
require $GLOBALS['Boot']->config->getConfigValue(array('dbmodel', 'parentpath')) . "Condition.php";

require $GLOBALS['Boot']->config->getConfigValue(array('dbmodel', 'path')) . "TestModel.php";
require $GLOBALS['Boot']->config->getConfigValue(array('dbmodel', 'path')) . "TestModel2.php";

class IndexController {
    private $DefaultController = true;
    private $DefaultAction = "index";

    public function indexAction() {
	$condition = new Condition("[c1] OR [c2]", array(
            "[c1]" =>   [
                            [TestModel::class, TestModel::FIELD_ID],
                            Condition::COMPARISON_EQUALS,
                            [Condition::CONDITION_CONST, 2]
                        ],
            "[c2]" =>   [
                            [TestModel::class, TestModel::FIELD_ID],
                            Condition::COMPARISON_EQUALS,
                            [Condition::CONDITION_CONST, 3]
                        ]
        ));

        $join = new Join(new TestModel2(), "[j1] AND [j2]", array(
            "[j1]" =>   [
                            [TestModel::class, TestModel::FIELD_ID],
                            Condition::COMPARISON_EQUALS,
                            [TestModel2::class, TestModel2::FIELD_TESTMODEL_ID]
                        ],
            "[j2]" =>   [
                            [TestModel2::class, TestModel2::FIELD_DELETED],
                            Condition::COMPARISON_EQUALS,
                            [Condition::CONDITION_CONST, 0]
                        ]
        ), Join::JOIN_INNER);

        $test = new TestModel();
        $test->find($condition, $join);

	$result = array();
        while ($test->next()) {
	    $result[] = print_r($test, true);
        }

	exit(json_encode($result, JSON_PRETTY_PRINT));
    }

    public function bluppAction() {

    }
}
