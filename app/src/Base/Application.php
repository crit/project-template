<?php
namespace Base;

class Application implements iApplication
{
    public
        $settings,
        $response;

    public function setup(\Base\Model\Response $response, $settings)
    {
        $this->response = $response;
        $this->settings = $settings;
    }

    public function execute()
    {
        return $this->response;
    }
}