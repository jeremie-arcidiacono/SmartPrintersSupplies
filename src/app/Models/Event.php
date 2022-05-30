<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

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
    protected $with = ['author', 'targetPrinter', 'targetSupply', 'targetUser'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idUser_author', 'idUser');
    }

    public function targetPrinter(): BelongsTo
    {
        return $this->belongsTo(Printer::class, 'idPrinter_target', 'idPrinter');
    }

    public function targetSupply(): BelongsTo
    {
        return $this->belongsTo(Supply::class, 'idSupply_target', 'idSupply');
    }

    public function targetModel(): BelongsTo
    {
        return $this->belongsTo(PrinterModel::class, 'idPrinterModel_target', 'idPrinterModel');
    }

    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idUser_target', 'idUser');
    }

    /**
     * Scope a query to only include the events made by a user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $authorId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthor($query, int $authorId = null): Builder
    {
        if ($authorId == null) {
            return $query;
        }
        else{
            return $query->where('idUser_author', '=', $authorId);
        }
    }

    /**
     * Scope a query to only include the events that match one or more types.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $authorId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeType($query, array $arrTypes = []): Builder
    {
        if (empty($arrTypes)) {
            return $query;
        }
        else{
            return $query->whereIn('action', $arrTypes);
        }
    }
}
