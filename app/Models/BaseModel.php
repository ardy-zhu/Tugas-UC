<?php

namespace App\Models;

use App\Core\Database;

abstract class BaseModel
{
    protected \mysqli $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
}
