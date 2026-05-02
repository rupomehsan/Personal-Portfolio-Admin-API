<?php

namespace Modules\Management\BlogManagement\Blog\Actions;

class GetAllData
{
    static $model = \Modules\Management\BlogManagement\Blog\Database\Models\Model::class;

    public static function execute()
    {
        try {

            $pageLimit = request()->input('limit') ?? 10;
            $orderByColumn = request()->input('sort_by_col') ?? 'id';
            $orderByType = request()->input('sort_type') ?? 'desc';
            $status = request()->input('status') ?? 'active';
            $fields = request()->input('fields') ?? '*';
            $start_date = request()->input('start_date');
            $end_date = request()->input('end_date');

                            $with = ['blog_category:id,title'];

            $condition = [];

            $data = self::$model::query();

                  if (request()->has('is_featured') && request()->input('is_featured')) {

                $data = $data->where('is_featured', request()->input('is_featured'));
               
            }

            if (request()->has('search') && request()->input('search')) {
                $searchKey = request()->input('search');
                $data = $data->where(function ($q) use ($searchKey) {
    $q->where('blog_category_id', 'like', '%' . $searchKey . '%');    

    $q->orWhere('title', 'like', '%' . $searchKey . '%');    

    $q->orWhere('description', 'like', '%' . $searchKey . '%');    

    $q->orWhere('content', 'like', '%' . $searchKey . '%');    

    $q->orWhere('reading_time', 'like', '%' . $searchKey . '%');    

    $q->orWhere('tags', 'like', '%' . $searchKey . '%');    

    $q->orWhere('publish_date', 'like', '%' . $searchKey . '%');    

    $q->orWhere('writer', 'like', '%' . $searchKey . '%');    

    $q->orWhere('thumbnail_image', 'like', '%' . $searchKey . '%');    

    $q->orWhere('images', 'like', '%' . $searchKey . '%');    

    $q->orWhere('blog_type', 'like', '%' . $searchKey . '%');    

    $q->orWhere('url', 'like', '%' . $searchKey . '%');    

    $q->orWhere('show_top', 'like', '%' . $searchKey . '%');    

    $q->orWhere('contributors', 'like', '%' . $searchKey . '%');    

    $q->orWhere('video_link', 'like', '%' . $searchKey . '%');    

    $q->orWhere('is_featured', 'like', '%' . $searchKey . '%');    

    $q->orWhere('is_published', 'like', '%' . $searchKey . '%');              

                });
            }

            if ($start_date && $end_date) {
                 if ($end_date > $start_date) {
                    $data->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                } elseif ($end_date == $start_date) {
                    $data->whereDate('created_at', $start_date);
                }
            }

            if ($status == 'trashed') {
                $data = $data->onlyTrashed();
            }

            if (request()->has('get_all') && (int)request()->input('get_all') === 1) {
                $data = $data
                    ->with($with)
                    ->select($fields)
                    ->where($condition)
                    ->where('status', $status)
                    ->limit($pageLimit)
                    ->orderBy($orderByColumn, $orderByType)
                    ->get();
                     return entityResponse($data);
            } else if ($status == 'trashed') {
                $data = $data
                    ->with($with)
                    ->select($fields)
                    ->where($condition)
                    ->orderBy($orderByColumn, $orderByType)
                    ->paginate($pageLimit);
            } else {
                $data = $data
                    ->with($with)
                    ->select($fields)
                    ->where($condition)
                    ->where('status', $status)
                    ->orderBy($orderByColumn, $orderByType)
                    ->paginate($pageLimit);
            }

            return entityResponse([
                ...$data->toArray(),
                "active_data_count" => self::$model::active()->count(),
                "inactive_data_count" => self::$model::inactive()->count(),
                "trashed_data_count" => self::$model::onlyTrashed()->count(),
            ]);

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}