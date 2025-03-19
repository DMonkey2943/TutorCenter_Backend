<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Class1 extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'classes';
    protected $fillable = [
        'num_of_students',
        'num_of_sessions',
        'grade_id',
        'address_id',
        'gender_tutor',
        'tuition',
        'request',
        'status',
        'level_id',
        'tutor_id',
        'parent_id',
        'deleted_at',
        'start_date',
        'end_date',
    ];
    protected $guarded = ['id'];

    protected $primaryKey = 'id';

    // Mutator: Chuyển đổi start_date trước khi lưu vào CSDL
    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id', 'id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id', 'id');
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class, 'tutor_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Parent1::class, 'parent_id', 'id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subjects', 'class_id', 'subject_id');
    }

    public function classTimes()
    {
        return $this->hasMany(ClassTime::class, 'class_id', 'id');
    }

    public function registeredTutors()
    {
        return $this->belongsToMany(Tutor::class, 'approve', 'class_id', 'tutor_id')
            ->using(Approve::class)->withPivot('status', 'created_at', 'updated_at');
    }

    public function report()
    {
        return $this->hasOne(Report::class, 'class_id', 'id');
    }

    public function approvals()
    {
        return $this->hasMany(Approve::class, 'class_id', 'id');
    }

    public function rate()
    {
        return $this->hasOne(Rate::class, 'class_id', 'id');
    }
}
