<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tuition extends Model
{
    use HasFactory;

    protected $table = 'tuitions';
    protected $fillable = ['range',];
    protected $guarded = ['id'];

    protected $primaryKey = 'id';

    public $timestamps = false;

    public function tutors()
    {
        return $this->hasMany(Tutor::class, 'tuition_id', 'id');
    }
}
