<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tutor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tutors';
    protected $fillable = [
        'user_id',
        'gender',
        'birthday',
        'address',
        'major',
        'school',
        'level_id',
        'tuition_id',
        'experiences',
        'avatar',
        'degree',
        'profile_status',
        'profile_reason',
    ];
    protected $guarded = ['id'];

    protected $primaryKey = 'id';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id', 'id');
    }

    public function tuition()
    {
        return $this->belongsTo(Tuition::class, 'tuition_id', 'id');
    }

    public function districts()
    {
        return $this->belongsToMany(District::class, 'tutor_districts', 'tutor_id', 'district_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'tutor_subjects', 'tutor_id', 'subject_id');
    }

    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'tutor_grades', 'tutor_id', 'grade_id');
    }

    public function classes()
    {
        return $this->hasMany(Class1::class, 'tutor_id', 'id');
    }

    public function registeredClasses()
    {
        return $this->belongsToMany(Class1::class, 'approve', 'tutor_id', 'class_id')
            ->withPivot('status', 'created_at', 'updated_at');
    }

    public function rates()
    {
        return $this->hasMany(Rate::class, 'tutor_id', 'id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'tutor_id', 'id');
    }
}
