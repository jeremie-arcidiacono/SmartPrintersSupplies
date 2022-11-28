<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;


/**
 * @property int $idPrinter
 * @property string $room
 * @property string $serialNumber
 * @property int $cti
 * @property Date $created_at
 * @property Date $deleted_at
 * @property int $printer_model_idPrinterModel
 * @property PrinterModel $printerModel
 */
class Printer extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'idPrinter_target');
    }
}
