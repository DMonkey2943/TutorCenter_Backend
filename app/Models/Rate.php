<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rate extends Model
{
    use HasFactory;

    protected $table = 'rates';
    protected $fillable = [
        'stars',
        'comment',
        'tutor_id',
        'parent_id',
    ];
    protected $guarded = ['id'];

    protected $primaryKey = 'id';

    public function tutor()
    {
        return $this->belongsTo(Tutor::class, 'tutor_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Parent1::class, 'parent_id', 'id');
    }
}
