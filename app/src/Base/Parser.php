<?php
namespace Base;
use Base\Model\Response;
use Base\Utility\Log;
use \Exception;

class Parser
{
    public
        $uri      = '',
        $app      = null,
        $app_name = '',
        $context  = '',
        $request  = array(),
        $response = null;

    public function __construct()
    {
        $this->response = new Response;
    }

    public function process()
    {
        try {
            $this->parseUri();
            $this->buildApp();

            $this->app->execute();
        } catch (Exception $e) {
            $msg = $e->getMessage();
            $id = $this->log($e);
            $this->response->data = sprintf("%s [%s]", $msg, $id);
        }
    }

    public function respond()
    {
        return json_encode($this->response);
    }

    public function log(Exception $e)
    {
        $log = new Log;
        return $log->exception($e);
    }

    public function parseUri()
    {
        $this->uri = $_GET['q'];

        $value = explode( '/', ltrim($this->uri, '/') );

        if (count($value) < 2) {
            throw new Exception("URI is too short to be valid.");
        }

        $this->context  = $value[0];
        $this->app_name = $value[1];
    }

    public function buildApp()
    {
        $class = implode('\\', array(ucfirst($this->context), ucfirst($this->app_name)));
        
        if (!class_exists($class)) {
            throw new Exception("Unable to find URI /{$this->context}/{$this->app_name}.");
        }

        $this->app = new $class;

        if (!method_exists($this->app, 'setup')) {
            throw new Exception("Unable to find URI /{$this->context}/{$this->app_name}.");
        }

        $this->app->setup($this->response, $this->request);
    }
}