<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='Modules/Management/ProductManagement/DigitalProduct/Database/Migrations/create_digital_products_table.php'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('digital_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id')->nullable();
            $table->string('title', 150)->nullable();
            $table->text('short_description')->nullable();
            $table->longtext('description')->nullable();
            $table->decimal('regular_price')->nullable();
            $table->decimal('sale_price')->nullable();
            $table->string('demo_url', 150)->nullable();
            $table->text('file_path')->nullable();
            $table->string('file_type', 50)->nullable();
            $table->string('version', 50)->nullable();
            $table->string('thumbnail_image', 100)->nullable();
            $table->text('gallery_images')->nullable();
            $table->text('features')->nullable();
            $table->text('tags')->nullable();
            $table->tinyInteger('is_active')->default(0);
            $table->tinyInteger('is_featured')->default(0);

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
        Schema::dropIfExists('digital_products');
    }
};