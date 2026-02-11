<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, decimal, boolean
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            ['key' => 'tax_rate', 'value' => '23.00', 'type' => 'decimal', 'description' => 'Tax rate percentage (IVA)', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'shipping_cost', 'value' => '5.00', 'type' => 'decimal', 'description' => 'Flat shipping cost in EUR', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'free_shipping_threshold', 'value' => '50.00', 'type' => 'decimal', 'description' => 'Order total for free shipping', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'max_cart_quantity_per_book', 'value' => '10', 'type' => 'integer', 'description' => 'Maximum quantity per book in cart', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'abandoned_cart_hours', 'value' => '1', 'type' => 'integer', 'description' => 'Hours before sending abandoned cart email', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
