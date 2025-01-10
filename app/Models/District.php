<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class District extends Model
{
    use HasFactory;

    protected $table = 'districts';
    protected $fillable = ['name',];
    protected $guarded = ['id'];

    protected $primaryKey = 'id';

    public $timestamps = false;

    public function wards()
    {
        return $this->hasMany(Ward::class, 'district_id', 'id');
    }

    public function tutors()
    {
        return $this->belongsToMany(Tutor::class, 'tutor_districts', 'district_id', 'tutor_id');
    }
}
