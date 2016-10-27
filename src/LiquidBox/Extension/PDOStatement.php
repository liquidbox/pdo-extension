<?php
/**
 * PHP PDO Statement Extension
 * @author Jonathan-Paul Marois <jonathanpaul.marois@gmail.com>
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
