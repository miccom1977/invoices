<?php

namespace App\Repositories;

use App\Constants\DatabaseTables;
use Illuminate\Support\Facades\DB;

class TokenRepository
{
    /** Metoda usuwa token dla sanctum dla userId
     * @param int $userId
     * @return void
     */
    public function deleteTokensByUserId(int $userId): void
    {
        DB::table(DatabaseTables::PERSONAL_ACCESS_TOKENS)
            ->where('tokenable_id', $userId)
            ->where('tokenable_type', 'App\Models\User')
            ->delete();
    }
}
