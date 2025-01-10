<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSubject extends Model
{
    use HasFactory;

    protected $table = 'class_subjects';
    protected $fillable = ['class_id', 'subject_id',];
    // protected $guarded = ['class_id', 'subject_id'];

    // protected $primaryKey = 'tt_id';

    public $timestamps = false;
}
