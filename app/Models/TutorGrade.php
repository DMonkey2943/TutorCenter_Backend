<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorGrade extends Model
{
    use HasFactory;

    protected $table = 'tutor_grades';
    protected $fillable = ['tutor_id', 'grade_id',];
    // protected $guarded = ['tt_id', 'grade_id'];

    // protected $primaryKey = 'tt_id';

    public $timestamps = false;
}
