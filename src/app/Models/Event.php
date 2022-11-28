<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Traits\Date;
use App\Enum\EventAction;

/**
 * @property int $idEvent
 * @property int $idUser_author
 * @property EventAction $action
 * @property string|null $comment
 * @property int|null $amount
 * @property Date $created_at
 *
 * @property int|null $idPrinter_target
 * @property int|null $idSupply_target
 * @property int|null $idPrinterModel_target
 * @property int|null $idUser_target
 */
class Event extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = "idEvent";

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['author', 'targetPrinter', 'targetSupply', 'targetModel', 'targetUser'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idUser_author', 'idUser');
    }

    public function targetPrinter(): BelongsTo
    {
        return $this->belongsTo(Printer::class, 'idPrinter_target', 'idPrinter')->withTrashed();
    }

    public function targetSupply(): BelongsTo
    {
        return $this->belongsTo(Supply::class, 'idSupply_target', 'idSupply')->withTrashed();
    }

    public function targetModel(): BelongsTo
    {
        return $this->belongsTo(PrinterModel::class, 'idPrinterModel_target', 'idPrinterModel')->withTrashed();
    }

    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idUser_target', 'idUser');
    }

    /**
     * Scope a query to only include the events that match one or more types.
     *
     * @param Builder $query
     * @param array $arrTypes
     * @return Builder
     */
    public function scopeType(Builder $query, array $arrTypes = []): Builder
    {
        if (empty($arrTypes)) {
            return $query;
        }
        else {
            return $query->whereIn('action', $arrTypes);
        }
    }
}
