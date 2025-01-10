<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ward extends Model
{
    use HasFactory;

    protected $table = 'wards';
    protected $fillable = ['name', 'district_id'];
    protected $guarded = ['id'];

    protected $primaryKey = 'id';

    public $timestamps = false;

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'ward_id', 'id');
    }
}
