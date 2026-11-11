<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletNumber extends Model
{
    protected $fillable = [
      'wallet_provider_id',
      'number',
      'status',
    ];
    public function provider() : BelongsTo
    {
        return $this->belongsTo(WalletProvider::class, 'wallet_provider_id');
    }
}
