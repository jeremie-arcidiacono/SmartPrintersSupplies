<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Printer extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = "idPrinter";

    public function supplies(): BelongsToMany
    {
        return $this->belongsToMany(Supply::class);
    }
}
