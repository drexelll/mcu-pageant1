<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contestants', function (Blueprint $table) {
            // adds deleted_at column — when filled, Laravel treats row as "deleted"
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('contestants', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
