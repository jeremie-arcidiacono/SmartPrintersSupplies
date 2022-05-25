<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supply extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;
    protected $primaryKey = "idSupply";

    public function models(): BelongsToMany
    {
        return $this->belongsToMany(PrinterModel::class, 'printerModel_supply', 'idSupply', 'idPrinterModel');
    }

    /**
     * Scope a query to only include a minumum quantity.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $quantityMin
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMinqty($query, $quantityMin = null)
    {
        if ($quantityMin == null) {
            return $query;
        }
        else{
            return $query->where('quantity', '>=', $quantityMin);
        }
    }

    /**
     * Scope a query to only include a maximum quantity.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $quantityMax
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMaxqty($query, $quantityMax = null)
    {
        if ($quantityMax == null) {
            return $query;
        }
        else{
            return $query->where('quantity', '<=', $quantityMax);
        }
    }
}
