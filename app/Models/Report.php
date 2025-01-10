<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';
    protected $fillable = [
        'content',
        'status',
        'response',
        'tutor_id',
        'class_id',
    ];
    protected $guarded = ['id'];

    protected $primaryKey = 'id';

    public function tutor()
    {
        return $this->belongsTo(Tutor::class, 'tutor_id', 'id');
    }

    public function class()
    {
        return $this->belongsTo(Class1::class, 'class_id', 'id');
    }
}
