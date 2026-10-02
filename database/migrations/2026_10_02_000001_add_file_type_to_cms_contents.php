<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE cms_content MODIFY COLUMN `type` ENUM('text','textarea','image','html','json','file') NOT NULL DEFAULT 'text'");
    }

    public function down(): void
    {
        DB::statement("UPDATE cms_content SET `type`='text' WHERE `type`='file'");
        DB::statement("ALTER TABLE cms_content MODIFY COLUMN `type` ENUM('text','textarea','image','html','json') NOT NULL DEFAULT 'text'");
    }
};
