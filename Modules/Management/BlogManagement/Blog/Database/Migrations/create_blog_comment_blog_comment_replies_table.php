<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/Modules/Management/BlogManagement/Blog/Database/Migrations/create_blog_comment_blog_comment_replies_table.php' 
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_comment_blog_comment_replies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('blog_comment_id')->nullable();
            $table->bigInteger('blog_comment_reply_id')->nullable();

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
        Schema::dropIfExists('blog_comment_blog_comment_replies');
    }
};