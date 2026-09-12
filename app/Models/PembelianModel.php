<?php

namespace App\Models;

class PembelianModel extends BaseModel
{
    public function getAll(): array
    {
        $result = $this->db->query('SELECT * FROM pembelian ORDER BY id DESC LIMIT 20');

        if ($result === false) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
