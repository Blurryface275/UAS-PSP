<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'phone', 'address'])]
class Supplier extends Model
{
    use HasFactory;
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
