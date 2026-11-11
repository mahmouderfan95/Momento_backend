<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WalletProvider extends Model
{
    protected $fillable = [
      'name',
      'status',
    ];
    public function numbers() : HasMany
    {
        return $this->hasMany(WalletNumber::class, 'wallet_provider_id');
    }
}
