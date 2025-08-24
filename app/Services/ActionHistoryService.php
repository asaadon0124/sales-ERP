<?php

namespace App\Services;

use App\Models\ActionHistory;

class ActionHistoryService
{
    public function action(string $title, string $desc, string $table, int $rowId, int $userId): void
    {
        ActionHistory::create([
            'title'      => $title,
            'desc'       => $desc,
            'table_name' => $table,
            'row_id'     => $rowId,
            'created_by' => $userId,
        ]);
    }
}
