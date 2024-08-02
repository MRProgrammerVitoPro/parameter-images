<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $table = 'images';
    protected $fillable = ['parameter_id', 'icon', 'icon_gray'];

    public function parameter()
    {
        return $this->belongsTo(Parameter::class);
    }
}
