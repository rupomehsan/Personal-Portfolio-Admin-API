<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/Modules/Management/ProductManagement/DigitalProduct/Database/Migrations/create_product_likes_table.php' 
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_likes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('digital_product_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('session_id', 100)->nullable();
            $table->string('ip', 100)->nullable();

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
        Schema::dropIfExists('product_likes');
    }
};