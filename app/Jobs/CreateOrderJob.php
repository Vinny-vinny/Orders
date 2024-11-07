<?php

namespace App\Jobs;

use App\Mail\OrderCreatedMail;
use App\Models\User;
use App\Repositories\OrderRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class CreateOrderJob implements ShouldQueue
{
    use Queueable;
    protected $order;
    protected $orderRespository;
    protected $user;
    /**
     * Create a new job instance.
     */
    public function __construct(OrderRepository $orderRepository, $order, User $user)
    {
        $this->order = $order;
        $this->orderRespository = $orderRepository;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
      $order = $this->orderRespository->create($this->order);
        try {
            Mail::to($this->user->email)->send(new OrderCreatedMail($order));
        }catch (\Exception $exception){
            info(__METHOD__.' '.$exception->getMessage());
        }
    }
}
