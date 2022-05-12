<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Supply extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = "idSupply";

    public function printers(): BelongsToMany
    {
        return $this->belongsToMany(Printer::class);
    }
}
