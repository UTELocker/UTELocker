<?php

namespace App\Http\Resources;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'amount' => $this->amount,
            'type' => TransactionType::getDescription($this->type),
            'status' => TransactionStatus::getDescription($this->status),
            'reference' => $this->reference,
            'content' => $this->content,
            'time' => $this->time ?? $this->created_at->format('Y-m-d H:i:s'),
            'balance' => $this->balance,
            'promotion_balance' => $this->promotion_balance,
        ];
    }
}
