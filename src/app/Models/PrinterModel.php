<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;

/**
 * @property int $idPrinterModel
 * @property string $brand
 * @property string $name
 * @property Date $created_at
 * @property Date $deleted_at
 * @property Printer[] $printers
 * @property Supply[] $supplies
 */
class PrinterModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $primaryKey = "idPrinterModel";
    protected $table = 'printerModels';

    public function printers(): HasMany
    {
        return $this->hasMany(Printer::class, 'printer_model_idPrinterModel', 'idPrinterModel');
    }

    public function supplies(): BelongsToMany
    {
        return $this->belongsToMany(Supply::class, 'printerModel_supply', 'idPrinterModel', 'idSupply');
    }
}
