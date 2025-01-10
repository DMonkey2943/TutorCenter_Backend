<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grade extends Model
{
    use HasFactory;

    protected $table = 'grades';
    protected $fillable = ['name',];
    protected $guarded = ['id'];

    protected $primaryKey = 'id';

    public $timestamps = false;

    public function tutors()
    {
        return $this->belongsToMany(Tutor::class, 'tutor_grades', 'grade_id', 'tutor_id');
    }

    public function classes()
    {
        return $this->hasMany(Class1::class, 'grade_id', 'id');
    }
}
