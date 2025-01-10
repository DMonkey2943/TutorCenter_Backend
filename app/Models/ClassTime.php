<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassTime extends Model
{
    use HasFactory;

    protected $table = 'class_times';
    protected $fillable = ['day', 'start', 'end', 'class_id'];
    protected $guarded = ['id'];

    protected $primaryKey = 'id';

    public $timestamps = false;

    public function class()
    {
        return $this->belongsTo(Class1::class, 'class_id', 'id');
    }
}
