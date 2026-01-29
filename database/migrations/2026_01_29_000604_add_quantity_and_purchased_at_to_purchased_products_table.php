<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('purchased_products', function (Blueprint $table) {
            if (!Schema::hasColumn('purchased_products', 'quantity')) {
                $table->unsignedInteger('quantity')->default(1)->after('price');
            }
            if (!Schema::hasColumn('purchased_products', 'purchased_at')) {
                $table->timestamp('purchased_at')->nullable()->after('quantity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('purchased_products', function (Blueprint $table) {
            if (Schema::hasColumn('purchased_products', 'purchased_at')) $table->dropColumn('purchased_at');
            if (Schema::hasColumn('purchased_products', 'quantity')) $table->dropColumn('quantity');
        });
    }
};
