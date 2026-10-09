<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleCategoryModel extends Model
{
    protected $table = 'vehicle_category_model';

    protected $fillable = ['category_id', 'model_keyword'];

    public function category()
    {
        return $this->belongsTo(VehicleCategory::class, 'category_id');
    }
}
