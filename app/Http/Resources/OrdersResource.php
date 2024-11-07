<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'amount' => $this->amount,
            'quantity' => $this->quantity,
            'product' => $this->product->name,
            'username' => $this->user->fullname,
            'date_created' => Carbon::parse($this->created_at)->format('Y-m-d'),
        ];
    }
}
