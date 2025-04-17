<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{

    protected $fillable = [
        'user_id',
        'nip',
        'phone',
        'address',
        'is_active',
        'gender',
        'position',
    ];

    protected $appends = ['gender_label', 'is_active_label'];

    public function getGenderlabelAttribute()
    {

        switch ($this->gender) {
            case '1':
                $label = "Man";
                break;

            default:
                $label = "Woman";
                break;
        }
        return $label;
    }

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
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
