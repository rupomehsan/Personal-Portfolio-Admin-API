<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='Modules/Management/ProductManagement/DigitalProduct/Database/Migrations/create_digital_product_comments_table.php'
     */
    public function up(): void
    {
        Schema::create('digital_product_comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('digital_product_id')->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('name', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('phone', 100)->nullable();
            $table->text('comment')->nullable();

            $table->bigInteger('creator')->unsigned()->nullable();
            $table->string('slug', 50)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('digital_product_comments');
    }
};
