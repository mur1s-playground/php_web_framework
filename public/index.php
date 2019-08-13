<?php

require "../vendor/reinerlanz/frame/src/Boot.php";

$boot = new Frame\Boot("../app/config/app.json");
$boot->run();