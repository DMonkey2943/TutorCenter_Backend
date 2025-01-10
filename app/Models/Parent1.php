<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parent1 extends Model
{
    use HasFactory;

    protected $table = 'parents';
    protected $fillable = ['gender', 'user_id', 'blocked_at'];
    protected $guarded = ['id'];

    protected $primaryKey = 'id';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function classes()
    {
        return $this->hasMany(Class1::class, 'parent_id', 'id');
    }

    public function rates()
    {
        return $this->hasMany(Rate::class, 'parent_id', 'id');
    }
}
