<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/Modules/Management/BlogManagement/Blog/Database/Migrations/add_name_to_blog_comment_replies_table.php'
     */
    public function up(): void
    {
        Schema::table('blog_comment_replies', function (Blueprint $table) {
            $table->string('name')->nullable()->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('blog_comment_replies', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
