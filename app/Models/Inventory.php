<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getQtyTotalAttribute()
    {
         // Jumlah total qty masuk
         $qty_in = $this->qty;

         // Jumlah total qty keluar berdasarkan relasi inventory_out
         $qty_out = $this->inventory_out ? $this->inventory_out->sum('qty') : 0;
 
         // Menghitung total qty dengan batas minimal 0
         $total = max($qty_in - $qty_out, 0);
 
         return $total;
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inventory_out()
    {
        return $this->hasMany(InventoryOut::class, 'inventories_id');
    }
}