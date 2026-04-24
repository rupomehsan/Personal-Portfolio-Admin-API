<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/Modules/Management/BlogManagement/Blog/Database/Migrations/create_blog_metas_table.php' 
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_metas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('blog_id')->nullable();
            $table->string('title', 150)->nullable();
            $table->longtext('description')->nullable();
            $table->string('keywords', 150)->nullable();
            $table->string('image', 100)->nullable();
            $table->string('image_alter_text', 150)->nullable();
            $table->string('image_title', 150)->nullable();

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
        Schema::dropIfExists('blog_metas');
    }
};