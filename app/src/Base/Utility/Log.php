<?php
namespace Base\Utility;

class Log
{
    private
        $cfg     = null,
        $con_err = null,
        $client  = null;

    public function __construct()
    {
        $this->config();
    }

    public function exception(\Exception $e)
    {
        if (!$this->connect()) {
            return $this->con_err;
        }

        $sql = "insert into exception_log (message, file, line, trace, ipaddress, date)"
        . " values (:msg, :file, :line, :trace, :ip, NOW())";
        $stmt = $this->client->prepare($sql);

        $stmt->bindParam(":msg", $e->getMessage(), \PDO::PARAM_STR);
        $stmt->bindParam(":file", $e->getFile(), \PDO::PARAM_STR);
        $stmt->bindParam(":line", $e->getLine(), \PDO::PARAM_INT);
        $stmt->bindParam(":trace", $e->getTraceAsString(), \PDO::PARAM_STR);
        $stmt->bindParam(":ip", $_SERVER['REMOTE_ADDR']);
        
        if (!$stmt->execute()) {
            return 0;
        }

        return $this->client->lastInsertId();
    }

    private function connect()
    {
        $connection = sprintf("mysql:host=%s;dbname=%s", $this->cfg['host'], $this->cfg['db']);

        try {
            $this->client = new \PDO($connection, $this->cfg['un'], $this->cfg['pw']);
            $this->client->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            $this->con_err = $e->getMessage();
            return false;
        }

        return true;
    }

    final private function config()
    {
        $this->cfg = include __DIR__ . "/_cfg/log.php";

        if (!is_array($this->cfg)) {
            throw new \Exception("Unable to include configuration for Log.");
        }
    }
}