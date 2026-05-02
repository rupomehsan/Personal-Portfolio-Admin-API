<?php

namespace Modules\Management\BlogManagement\Blog\Actions;

use Modules\Management\BlogManagement\BlogCategory\Database\Models\Model as BlogCategoryModel;

class GetBlogCategoriesWithCount
{
    static $blogModel = \Modules\Management\BlogManagement\Blog\Database\Models\Model::class;

    public static function execute()
    {
        try {
            $categories = BlogCategoryModel::where('status', 'active')
                ->select('id', 'title', 'slug', 'icon')
                ->get();

            $categories = $categories->map(function ($cat) {
                $cat->blog_count = self::$blogModel::where('blog_category_id', $cat->id)
                    ->where('status', 'active')
                    ->count();
                return $cat;
            })
            ->filter(fn($cat) => $cat->blog_count > 0)
            ->sortByDesc('blog_count')
            ->values();

            return entityResponse($categories);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
