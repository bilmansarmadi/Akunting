<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ChartOfAccountType extends Model
{
    protected $fillable = [
        'name',
        'created_by',
    ];

    public function ChartOfAccountSubType()
    {
        return $this->hasMany(ChartOfAccountSubType::class);
    }

}
