<?php
namespace Tests\MyApp;

class SomethingTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->app = new \MyApp\Something();
        $this->app->setup( new \Base\Model\Response );
    }

    /**
     * Expected Use Case for a Rep
     */
    public function testExpectedUsage()
    {
        $message = "This should be in the output's data.";

        $_GET['v'] = $message;

        $this->app->execute();

        $this->assertTrue(
            $this->app->response->data === $message, 
            print_r($this->app->response, true) . "\nDATA DID NOT MATCH"
        );
    }
}