<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Equipment;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'cat_name',
        'p_cat'
    ];

    public function equipments()
    {
        return $this->hasMany(Equipment::class, 'cat_id', 'id');
    }

    public function subCategories()
    {
        return $this->hasMany(Category::class, 'p_cat', 'id');
    }
}
