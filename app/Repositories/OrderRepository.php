<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;

class OrderRepository extends BaseRepository implements BaseRepositoryInterface
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    /**
     * @param string $term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function search(string $term) :Builder
    {
        return $this->model->with(['user', 'product'])
            ->where('order_number', 'like', '%' . $term . '%')
            ->orWhereHas('user', function ($query) use ($term) {
                $query->where('fullname', 'like', '%' . $term . '%')
                    ->orWhere('email', 'like', '%' . $term . '%');
            })
            ->orWhereHas('product', function ($query) use ($term) {
                $query->where('name', 'like', '%' . $term . '%');
            });
    }

    /**
     * @param $orders
     * @return array[]
     */
    public function getPaginationLinks($orders)
    {
        return [
            [
                'url' => $orders->previousPageUrl(),
                'label' => '&laquo; Previous',
                'active' => $orders->onFirstPage(),
            ],
            [
                'url' => $orders->url($orders->currentPage()),
                'label' => (string) $orders->currentPage(),
                'active' => true,
            ],
            [
                'url' => $orders->nextPageUrl(),
                'label' => 'Next &raquo;',
                'active' => $orders->hasMorePages(),
            ],
        ];
    }

    /**
     * @return string
     * @throws \Random\RandomException
     */
    public function generateOrderNumber(): string
    {
        // Generate the order number
        do {
            $orderNumber = 'ORN-' . str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}
