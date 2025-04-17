<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offices extends Model
{
    protected $fillable = [
        'office_name',
        'office_phone',
        'office_address',
        'office_lat',
        'office_long',
        'is_active'
    ];

    protected $appends = ['is_active_label'];

    public function getIsActivelabelAttribute()
    {

        switch ($this->is_active) {
            case '1':
                $label = "Active";
                break;

            default:
                $label = "Inactive";
                break;
        }
        return $label;
    }
}
