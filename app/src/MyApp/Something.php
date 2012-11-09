<?php
namespace MyApp;

class Something extends \Base\Application
{
    public function execute()
    {
        $value = $_GET['v'];

        $this->response->success = true;
        $this->response->data = $value;

        parent::execute();
    }
}