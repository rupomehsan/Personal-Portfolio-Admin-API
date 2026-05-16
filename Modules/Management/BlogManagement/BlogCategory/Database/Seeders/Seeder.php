<?php
namespace Modules\Management\BlogManagement\BlogCategory\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Modules\Management\BlogManagement\BlogCategory\Database\Seeders\Seeder"
     */
    static $model = \Modules\Management\BlogManagement\BlogCategory\Database\Models\Model::class;

    public function run(): void
    {
        $table = (new self::$model)->getTable();

        Schema::disableForeignKeyConstraints();
        DB::table($table)->truncate();
        Schema::enableForeignKeyConstraints();

        $rows = json_decode(
            file_get_contents(__DIR__ . '/data/blog_categories.json'),
            true
        );

        $now = now();
        $payload = array_map(static function (array $r) use ($now) {
            return [
                'id'          => $r['id'],
                'title'       => $r['title'],
                'description' => $r['description'],
                'icon'        => $r['icon'],
                'creator'     => $r['creator'],
                'slug'        => $r['slug'],
                'status'      => $r['status'] ?: 'active',
                'created_at'  => $r['created_at'] ?: $now,
                'updated_at'  => $r['updated_at'] ?: $now,
            ];
        }, $rows);

        foreach (array_chunk($payload, 100) as $chunk) {
            DB::table($table)->insert($chunk);
        }
    }
}
