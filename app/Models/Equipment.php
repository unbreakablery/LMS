<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Equipment extends Model
{
    protected $table = 'equipment';

    protected $fillable = [
        'equ_code',
        'equ_name',
        'equ_desc',
        'equ_image',
        'equ_total_qnt',
        'equ_current_qnt',
        'equ_status',
        'cat_id'
    ];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'cat_id');
    }

    public function getStatusName()
    {
        $this->attributes['status_name'] = getStatusName('equipment', $this->equ_status);
    }
}
