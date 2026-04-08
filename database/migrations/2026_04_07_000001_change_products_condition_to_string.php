<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Convert products.condition from ENUM to VARCHAR(50) so we can store
     * additional values like 'ex-us' (previously only 'new', 'ex-uk',
     * 'refurbished' were allowed).
     */
    public function up(): void
    {
        // Use raw SQL because Doctrine DBAL can't always alter ENUMs cleanly
        DB::statement("ALTER TABLE products MODIFY condition VARCHAR(50) NOT NULL DEFAULT 'new'");
    }

    public function down(): void
    {
        // Revert any ex-us values back to ex-uk before restoring the enum
        DB::statement("UPDATE products SET `condition` = 'ex-uk' WHERE `condition` = 'ex-us'");
        DB::statement("ALTER TABLE products MODIFY `condition` ENUM('new', 'ex-uk', 'refurbished') NOT NULL DEFAULT 'new'");
    }
};
