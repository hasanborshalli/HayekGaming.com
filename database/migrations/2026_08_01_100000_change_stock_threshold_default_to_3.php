<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE products ALTER COLUMN stock_threshold SET DEFAULT 3');
        DB::statement('ALTER TABLE watches ALTER COLUMN stock_threshold SET DEFAULT 3');

        // Bring existing rows still on the old default up to date with the new one.
        DB::table('products')->where('stock_threshold', 5)->update(['stock_threshold' => 3]);
        DB::table('watches')->where('stock_threshold', 5)->update(['stock_threshold' => 3]);
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE products ALTER COLUMN stock_threshold SET DEFAULT 5');
        DB::statement('ALTER TABLE watches ALTER COLUMN stock_threshold SET DEFAULT 5');

        DB::table('products')->where('stock_threshold', 3)->update(['stock_threshold' => 5]);
        DB::table('watches')->where('stock_threshold', 3)->update(['stock_threshold' => 5]);
    }
};
