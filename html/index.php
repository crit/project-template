<?php
require dirname(__DIR__) . "/app/vendor/autoload.php";

$parser = new Base\Parser();

$parser->process();

header('Content-type: text/json');
echo $parser->respond();