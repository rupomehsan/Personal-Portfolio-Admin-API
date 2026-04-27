<?php

namespace Modules\Management\ProjectManagement\Project\Controller;

use Modules\Management\ProjectManagement\Project\Actions\GetAllData;
use Modules\Management\ProjectManagement\Project\Actions\DestroyData;
use Modules\Management\ProjectManagement\Project\Actions\GetSingleData;
use Modules\Management\ProjectManagement\Project\Actions\StoreData;
use Modules\Management\ProjectManagement\Project\Actions\UpdateData;
use Modules\Management\ProjectManagement\Project\Actions\UpdateStatus;
use Modules\Management\ProjectManagement\Project\Actions\SoftDelete;
use Modules\Management\ProjectManagement\Project\Actions\RestoreData;
use Modules\Management\ProjectManagement\Project\Actions\ImportData;
use Modules\Management\ProjectManagement\Project\Actions\BulkActions;
use Modules\Management\ProjectManagement\Project\Actions\GetAllProjectComments;
use Modules\Management\ProjectManagement\Project\Actions\GetProjectComments;
use Modules\Management\ProjectManagement\Project\Actions\GetProjectCommentReplies;
use Modules\Management\ProjectManagement\Project\Actions\SubmitProjectComment;
use Modules\Management\ProjectManagement\Project\Actions\SubmitProjectCommentReply;
use Modules\Management\ProjectManagement\Project\Validations\BulkActionsValidation;
use Modules\Management\ProjectManagement\Project\Validations\DataStoreValidation;
use Modules\Management\ProjectManagement\Project\Validations\ProjectCommentValidation;
use Modules\Management\ProjectManagement\Project\Validations\ProjectCommentReplyValidation;
use App\Http\Controllers\Controller as ControllersController;


class Controller extends ControllersController
{

    public function index()
    {
        $data = GetAllData::execute();
        return $data;
    }

    public function store(DataStoreValidation $request)
    {
        $data = StoreData::execute($request);
        return $data;
    }

    public function show($slug)
    {
        $data = GetSingleData::execute($slug);
        return $data;
    }

    public function update(DataStoreValidation $request, $slug)
    {
        $data = UpdateData::execute($request, $slug);
        return $data;
    }

    public function updateStatus()
    {
        $data = UpdateStatus::execute();
        return $data;
    }

    public function softDelete()
    {
        $data = SoftDelete::execute();
        return $data;
    }

    public function destroy($slug)
    {
        $data = DestroyData::execute($slug);
        return $data;
    }

    public function restore()
    {
        $data = RestoreData::execute();
        return $data;
    }

    public function import()
    {
        $data = ImportData::execute();
        return $data;
    }

    public function bulkAction(BulkActionsValidation $request)
    {
        $data = BulkActions::execute($request);
        return $data;
    }

    // Project Comment Methods
    public function getAllProjectComments()
    {
        return GetAllProjectComments::execute();
    }

    public function getProjectComments($project_id)
    {
        return GetProjectComments::execute($project_id);
    }

    public function getProjectCommentReplies($comment_id)
    {
        return GetProjectCommentReplies::execute($comment_id);
    }

    public function submitProjectComment(ProjectCommentValidation $request)
    {
        return SubmitProjectComment::execute($request);
    }

    public function submitProjectCommentReply(ProjectCommentReplyValidation $request)
    {
        return SubmitProjectCommentReply::execute($request);
    }
}
