<?php
/**
 * LiquidBox PHP PDO Statement Extension
 */
namespace LiquidBox\Extension;

class PDOStatement extends \PDOStatement
{
    protected $pdo;

    protected function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
}
