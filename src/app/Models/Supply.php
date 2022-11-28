<?php

namespace App\Models;

use Carbon\Traits\Date;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $idSupply
 * @property string $brand
 * @property string $code
 * @property int $quantity
 * @property Date $created_at
 * @property PrinterModel[] $models
 */
class Supply extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $primaryKey = "idSupply";

    public function models(): BelongsToMany
    {
        return $this->belongsToMany(PrinterModel::class, 'printerModel_supply', 'idSupply', 'idPrinterModel');
    }

    /**
     * Scope a query to only include a minimum quantity.
     *
     * @param Builder $query
     * @param int|null $quantityMin
     * @return Builder
     */
    public function scopeMinqty(Builder $query, int $quantityMin = null): Builder
    {
        if ($quantityMin == null) {
            return $query;
        }
        else {
            return $query->where('quantity', '>=', $quantityMin);
        }
    }

    /**
     * Scope a query to only include a maximum quantity.
     *
     * @param Builder $query
     * @param int|null $quantityMax
     * @return Builder
     */
    public function scopeMaxqty(Builder $query, int $quantityMax = null): Builder
    {
        if ($quantityMax == null) {
            return $query;
        }
        else {
            return $query->where('quantity', '<=', $quantityMax);
        }
    }
}
