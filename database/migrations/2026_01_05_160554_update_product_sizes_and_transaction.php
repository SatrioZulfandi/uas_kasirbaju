<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('size');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('sizes')->nullable()->after('name');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('size')->nullable()->after('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('size');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('sizes');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('size')->nullable()->after('name');
        });
    }
};
