<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionChecklistItem extends Model
{
    use HasFactory;

    protected $table = 'inspection_checklist_item';

    protected $fillable = [
        'package_id', 'item_name', 'category', 'description', 'sort_order',
    ];

    public function package()
    {
        return $this->belongsTo(InspectionPackage::class, 'package_id');
    }
}
