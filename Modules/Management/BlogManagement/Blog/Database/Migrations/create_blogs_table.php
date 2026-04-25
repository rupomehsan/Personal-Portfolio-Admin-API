<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/app/Modules/Management/BlogManagement/Blog/Database/create_blogs_table.php'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('blog_category_id')->nullable();
            $table->string('title', 150)->nullable();
            $table->text('description')->nullable();
            $table->longtext('content')->nullable();
            $table->integer('reading_time')->nullable();
            $table->text('tags')->nullable();
            $table->datetime('publish_date')->nullable();
            $table->bigInteger('writer')->nullable();
            $table->string('thumbnail_image', 150)->nullable();
            $table->text('images')->nullable();
            $table->enum('blog_type', ['news','tutorial','opinion'])->nullable();
            $table->string('url', 100)->nullable();
            $table->enum('show_top', ['yes','no'])->nullable();
            $table->json('contributors')->nullable();
            $table->string('video_link', 50)->nullable();
            $table->tinyInteger('is_featured')->default(0);
            $table->tinyInteger('is_published')->default(0);

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
        Schema::dropIfExists('blogs');
    }
};