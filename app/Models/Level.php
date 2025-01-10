<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    use HasFactory;

    protected $table = 'levels';
    protected $fillable = ['name',];
    protected $guarded = ['id'];

    protected $primaryKey = 'id';

    public $timestamps = false;

    public function tutors()
    {
        return $this->hasMany(Tutor::class, 'level_id', 'id');
    }

    public function classes()
    {
        return $this->hasMany(Class1::class, 'level_id', 'id');
    }
}
