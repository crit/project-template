<?php
namespace Base;

interface iApplication
{
    public function setup(\Base\Model\Response $response, $values);
    public function execute();
}