<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorSubject extends Model
{
    use HasFactory;

    protected $table = 'tutor_subjects';
    protected $fillable = ['tutor_id', 'subject_id',];
    // protected $guarded = ['tt_id', 'subject_id'];

    // protected $primaryKey = 'tt_id';

    public $timestamps = false;
}
