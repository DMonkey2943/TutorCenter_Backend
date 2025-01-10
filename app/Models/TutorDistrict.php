<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorDistrict extends Model
{
    use HasFactory;

    protected $table = 'tutor_districts';
    protected $fillable = ['tutor_id', 'district_id',];
    // protected $guarded = ['tt_id', 'district_id'];

    // protected $primaryKey = 'tt_id';

    public $timestamps = false;
}
