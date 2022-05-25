<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrinterModel extends Model
{
    use HasFactory, SoftDeletes;

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
