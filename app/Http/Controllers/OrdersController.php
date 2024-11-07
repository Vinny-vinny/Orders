<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrdersResource;
use App\Jobs\CreateOrderJob;
use App\Repositories\OrderRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrdersController extends BaseController
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
     $this->orderRepository = $orderRepository;
    }

    /** Display a listing of orders with pagination and optional search
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $term = $request->input('search', null);

        if ($term) {
            // If there's a search term, use the search method to filter and paginate
            $orders = $this->orderRepository->search($term)->paginate();
        } else {
            // If there's no search term, just paginate the orders
            $orders = $this->orderRepository->paginate($perPage, $page);
        }
        // Transform paginated orders with OrdersResource
        $ordersCollection = OrdersResource::collection($orders);

        // Return the formatted response with pagination
        return response()->json([
            'current_page' => $orders->currentPage(),
            'data' => $ordersCollection,
            'first_page_url' => $orders->url(1),
            'from' => $orders->firstItem(),
            'last_page' => $orders->lastPage(),
            'last_page_url' => $orders->url($orders->lastPage()),
            'links' => $this->orderRepository->getPaginationLinks($orders),
            'next_page_url' => $orders->nextPageUrl(),
            'path' => $orders->path(),
            'per_page' => $orders->perPage(),
            'prev_page_url' => $orders->previousPageUrl(),
            'to' => $orders->lastItem(),
            'total' => $orders->total(),
        ]);
    }

    /** Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     * @throws \Random\RandomException
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'product_id' => 'required|numeric|exists:products,id',
           'amount' => 'required|numeric|min:1',
           'quantity' => 'required|numeric|min:1',
        ],[
            'product_id.required'=>'product id is required',
            'product_id.numeric'=>'product id must be numeric',
            'product_id.exists'=>'product id must exists',
            'amount.required'=>'amount is required',
            'amount.quantity'=>'quantity is required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error',$validator->errors(),422);
        }
        $requestData = $request->all();
        $requestData['order_number'] = $this->orderRepository->generateOrderNumber();
        $user = $request->user();
        CreateOrderJob::dispatch($this->orderRepository, $requestData, $user);
        return $this->sendResponse(['user_id'=>$user->id,'product_id'=>$request->product_id,'amount'=>$request->amount,'quantity'=>$request->quantity],'Product has been added to the queue');

    }
}
