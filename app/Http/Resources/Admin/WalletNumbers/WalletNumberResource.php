<?php

namespace App\Http\Resources\Admin\WalletNumbers;

use App\Http\Resources\Admin\WalletProviders\WalletProviderResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletNumberResource extends JsonResource
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
            'wallet_number' => $this->number,
            'status' => $this->status,
            'provider' => WalletProviderResource::make($this->whenLoaded('provider')),
        ];
    }
}
