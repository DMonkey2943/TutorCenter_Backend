<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';

    protected $fillable = ['name',];
    protected $guarded = ['id'];

    protected $primaryKey = 'id';

    public $timestamps = false;

    public function tutors()
    {
        return $this->belongsToMany(Tutor::class, 'tutor_subjects', 'subject_id', 'tutor_id');
    }

    public function classes()
    {
        return $this->belongsToMany(Class1::class, 'class_subjects', 'subject_id', 'class_id');
    }
}
