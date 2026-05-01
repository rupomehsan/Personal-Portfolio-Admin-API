<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Controller;

use Modules\Management\ProductManagement\DigitalProduct\Actions\GetAllData;
use Modules\Management\ProductManagement\DigitalProduct\Actions\DestroyData;
use Modules\Management\ProductManagement\DigitalProduct\Actions\GetSingleData;
use Modules\Management\ProductManagement\DigitalProduct\Actions\StoreData;
use Modules\Management\ProductManagement\DigitalProduct\Actions\UpdateData;
use Modules\Management\ProductManagement\DigitalProduct\Actions\UpdateStatus;
use Modules\Management\ProductManagement\DigitalProduct\Actions\SoftDelete;
use Modules\Management\ProductManagement\DigitalProduct\Actions\RestoreData;
use Modules\Management\ProductManagement\DigitalProduct\Actions\ImportData;
use Modules\Management\ProductManagement\DigitalProduct\Actions\BulkActions;
use Modules\Management\ProductManagement\DigitalProduct\Actions\GetAllDigitalProductComments;
use Modules\Management\ProductManagement\DigitalProduct\Actions\GetDigitalProductComments;
use Modules\Management\ProductManagement\DigitalProduct\Actions\GetDigitalProductCommentReplies;
use Modules\Management\ProductManagement\DigitalProduct\Actions\SubmitDigitalProductComment;
use Modules\Management\ProductManagement\DigitalProduct\Actions\SubmitDigitalProductCommentReply;
use Modules\Management\ProductManagement\DigitalProduct\Actions\DestroyDigitalProductComment;
use Modules\Management\ProductManagement\DigitalProduct\Actions\GetSingleDigitalProduct;
use Modules\Management\ProductManagement\DigitalProduct\Actions\SubmitDigitalProductLike;
use Modules\Management\ProductManagement\DigitalProduct\Actions\SubmitDigitalProductOrder;
use Modules\Management\ProductManagement\DigitalProduct\Actions\ProductOrder\GetAllProductOrders;
use Modules\Management\ProductManagement\DigitalProduct\Actions\ProductOrder\GetProductOrderById;
use Modules\Management\ProductManagement\DigitalProduct\Actions\ProductOrder\UpdateProductOrderStatus;
use Modules\Management\ProductManagement\DigitalProduct\Actions\ProductOrder\ProvideOrderAccess;
use Modules\Management\ProductManagement\DigitalProduct\Actions\ProductOrder\SendOrderReminder;
use Modules\Management\ProductManagement\DigitalProduct\Actions\ProductOrder\AddOrderNote;
use Modules\Management\ProductManagement\DigitalProduct\Actions\ProductOrder\DeleteProductOrder;
use Modules\Management\ProductManagement\DigitalProduct\Validations\BulkActionsValidation;
use Modules\Management\ProductManagement\DigitalProduct\Validations\DataStoreValidation;
use Modules\Management\ProductManagement\DigitalProduct\Validations\DigitalProductCommentValidation;
use Modules\Management\ProductManagement\DigitalProduct\Validations\DigitalProductCommentReplyValidation;
use Modules\Management\ProductManagement\DigitalProduct\Validations\SubmitDigitalProductOrderValidation;
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

    // Digital Product Comment Methods
    public function getAllDigitalProductComments()
    {
        return GetAllDigitalProductComments::execute();
    }

    public function getDigitalProductComments($digital_product_id)
    {
        return GetDigitalProductComments::execute($digital_product_id);
    }

    public function getDigitalProductCommentReplies($comment_id)
    {
        return GetDigitalProductCommentReplies::execute($comment_id);
    }

    public function submitDigitalProductComment(DigitalProductCommentValidation $request)
    {
        return SubmitDigitalProductComment::execute($request);
    }

    public function submitDigitalProductCommentReply(DigitalProductCommentReplyValidation $request)
    {
        return SubmitDigitalProductCommentReply::execute($request);
    }

    public function destroyDigitalProductComment($id)
    {
        return DestroyDigitalProductComment::execute($id);
    }

    public function getSingleDigitalProduct($slug)
    {
        return GetSingleDigitalProduct::execute($slug);
    }

    public function submitDigitalProductLike($digital_product_id)
    {
        return SubmitDigitalProductLike::execute($digital_product_id);
    }

    public function submitDigitalProductOrder(SubmitDigitalProductOrderValidation $request)
    {
        return SubmitDigitalProductOrder::execute($request);
    }

    // Product Order Management Methods
    public function getAllProductOrders()
    {
        return GetAllProductOrders::execute();
    }

    public function getProductOrderById($order_id)
    {
        return GetProductOrderById::execute($order_id);
    }

    public function updateProductOrderStatus($order_id)
    {
        return UpdateProductOrderStatus::execute($order_id);
    }

    public function provideOrderAccess($order_id)
    {
        return ProvideOrderAccess::execute($order_id);
    }

    public function sendOrderReminder($order_id)
    {
        return SendOrderReminder::execute($order_id);
    }

    public function addOrderNote($order_id)
    {
        return AddOrderNote::execute($order_id);
    }

    public function deleteProductOrder($order_id)
    {
        return DeleteProductOrder::execute($order_id);
    }
}
