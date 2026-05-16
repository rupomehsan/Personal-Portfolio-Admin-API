<?php
namespace Modules\Management\BlogManagement\Blog\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Modules\Management\BlogManagement\Blog\Database\Seeders\Seeder"
     */
    static $model = \Modules\Management\BlogManagement\Blog\Database\Models\Model::class;

    public function run(): void
    {
        $table = (new self::$model)->getTable();

        Schema::disableForeignKeyConstraints();
        DB::table($table)->truncate();
        Schema::enableForeignKeyConstraints();

        $rows = json_decode(
            file_get_contents(__DIR__ . '/data/blogs.json'),
            true
        );

        $now = now();
        $fitSlug = static function (?string $slug, int $max = 50): ?string {
            if ($slug === null || strlen($slug) <= $max) return $slug;
            if (preg_match('/^(.*)(-\d+)$/', $slug, $m)) {
                $suffix = $m[2];
                $prefix = $m[1];
                return substr($prefix, 0, $max - strlen($suffix)) . $suffix;
            }
            return substr($slug, 0, $max);
        };
        $cut = static fn (?string $v, int $n) => $v === null ? null : (strlen($v) <= $n ? $v : substr($v, 0, $n));
        $excerpt = static function (?string $html, int $max = 500): ?string {
            if ($html === null) return null;
            $plain = trim(preg_replace('/\s+/', ' ', strip_tags($html)));
            if ($plain === '') return null;
            return mb_strlen($plain) <= $max ? $plain : mb_substr($plain, 0, $max);
        };

        $payload = array_map(static function (array $r) use ($now, $fitSlug, $cut, $excerpt) {
            return [
                'id'               => $r['id'],
                'blog_category_id' => $r['blog_category_id'],
                'title'            => $cut($r['title'], 150),
                'description'      => $excerpt($r['description'], 500),
                'content'          => $r['content'],
                'reading_time'     => $r['reading_time'],
                'tags'             => $r['tags'],
                'publish_date'     => $r['publish_date'],
                'writer'           => $r['writer'],
                'thumbnail_image'  => $r['thumbnail_image'],
                'images'           => $r['images'],
                'blog_type'        => $r['blog_type'],
                'url'              => $r['url'],
                'show_top'         => $r['show_top'] ?: 'no',
                'contributors'     => $r['contributors'] !== null ? json_encode($r['contributors']) : null,
                'video_link'       => $r['video_link'],
                'is_featured'      => (int) $r['is_featured'],
                'is_published'     => (int) $r['is_published'],
                'creator'          => $r['creator'],
                'slug'             => $fitSlug($r['slug'], 50),
                'status'           => $r['status'] ?: 'active',
                'created_at'       => $r['created_at'] ?: $now,
                'updated_at'       => $r['updated_at'] ?: $now,
            ];
        }, $rows);

        foreach (array_chunk($payload, 5) as $chunk) {
            DB::table($table)->insert($chunk);
        }
    }
}
