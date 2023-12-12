<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ChartOfAccountSubType extends Model
{
    protected $fillable = [
        'name',
        'type',
        'created_by',
    ];

    public function ChartOfAccountSubType()
    {
        return $this->belongsTo(ChartOfAccountSubType::class);
    }
}
