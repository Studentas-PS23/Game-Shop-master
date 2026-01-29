<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('cart_products', 'quantity')) {
            Schema::table('cart_products', function (Blueprint $table) {
                $table->unsignedInteger('quantity')->default(1)->after('price');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('cart_products', 'quantity')) {
            Schema::table('cart_products', function (Blueprint $table) {
                $table->dropColumn('quantity');
            });
        }
    }
};
