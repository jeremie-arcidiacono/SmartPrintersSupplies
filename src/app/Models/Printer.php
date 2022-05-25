<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Printer extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;
    protected $primaryKey = "idPrinter";

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['model'];

    public function model(): BelongsTo
    {
        return $this->belongsTo(PrinterModel::class, 'printer_model_idPrinterModel');
    }
}
