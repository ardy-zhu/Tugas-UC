<?php

namespace App\Models;

class ProduksiModel extends BaseModel
{
    public function getAll(): array
    {
        $result = $this->db->query('SELECT * FROM produksi ORDER BY id DESC LIMIT 20');

        if ($result === false) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
