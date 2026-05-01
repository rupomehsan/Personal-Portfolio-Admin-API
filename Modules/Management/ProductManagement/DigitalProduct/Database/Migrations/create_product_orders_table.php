<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/Modules/Management/ProductManagement/DigitalProduct/Database/Migrations/create_product_orders_table.php' 
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('digital_product_id')->nullable();
            $table->string('first_name', 150)->nullable();
            $table->string('last_name', 150)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('phone', 150)->nullable();
            $table->string('trx_number', 150)->nullable();
            $table->json('payment_details')->nullable();
            $table->enum('payment_status', ['pending','due','compelete'])->nullable();

            $table->bigInteger('creator')->unsigned()->nullable();
            $table->string('slug', 50)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_orders');
    }
};