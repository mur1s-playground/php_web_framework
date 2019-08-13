<?php

namespace Frame;

require "Config.php";
require "DB/DBO.php";

$GLOBALS['Boot'] = null;

class Boot {
    private $config = null;
    private $DBO = null;

    public function __construct($config_path) {
        $this->config = new Config($config_path);
        $this->DBO = new DBO($this->config);
        $GLOBALS['Boot'] = &$this;
    }

    public function getDBO() {
        return $this->DBO;
    }

    public function run() {
        $request_uri = ltrim($_SERVER['REQUEST_URI'],'/');

        $modules_cfg = new Config($this->config->getConfigValue(array("modules", "config")));

        $request_params = explode('/', $request_uri);
        if (sizeof($request_params) == 0) die("Not enough parameters given");
        $module_name = null;
        if (sizeof($request_params) > 0 && strlen($request_params[0]) > 0) {
            if ($modules_cfg->getConfigValue($request_params[0]) != null) {
                $module_name = $request_params[0];
            }
        } else {
            $module_name = $this->config->getConfigValue(array('modules', 'defaultModule'));
        }

        $controller_cfg = $modules_cfg->getConfigValue(array($module_name, 'controllers'));
        $controller_name = null;
        if ($module_name != null) {
            if (sizeof($request_params) > 1 && strlen($request_params[1]) > 0) {
                $controller_name_lower = strtolower($request_params[1]);
                $available_controllers = array_keys($controller_cfg);
                foreach ($available_controllers as $available_controller) {
                    if ($controller_name_lower == strtolower($available_controller)) {
                        $controller_name = $available_controller;
                        break;
                    }
                }
            } else {
                $controller_name = $modules_cfg->getConfigValue(array($module_name, 'defaultController'));
            }
        } else {
            die(json_encode('no module given'));
        }

        if ($controller_name != null) {
            require $this->config->getConfigValue(array('modules', 'path')) . $module_name . '/' . $controller_name . 'Controller.php';
            $controller_full_name = "\\" . $this->config->getConfigValue(array('modules', 'namespace')) . "\\" . $controller_name . 'Controller';
            $controller_instance = new $controller_full_name();
            $controller_action = null;
            if (sizeof($request_params) > 2 && strlen($request_params[2]) > 0) {
               foreach ($controller_cfg[$controller_cfg]['actions'] as $available_action) {
                   if (strtolower($request_params[2]) == strtolower($available_action)) {
                       $controller_action = $available_action . 'Action';
                       break;
                   }
               }
            }
            if ($controller_action == null && array_key_exists('defaultAction', $controller_cfg[$controller_name])) {
                    $controller_action = $controller_cfg[$controller_name]['defaultAction'] . 'Action';
            }
            if (!is_null($controller_action)) {
                $controller_instance->$controller_action();
            } else {
                die(json_encode('no action given'));
            }
        } else {
            die(json_encode('no controller given'));
        }
    }
}
