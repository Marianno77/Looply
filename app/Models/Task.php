<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','repeat_type','repeat_interval','exclude_days','exclude_holidays','start_date','end_date','completed'];

    protected $casts = [
        'exclude_days' => 'array',
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function instance()
    {
        return $this->hasMany(TaskInstance::class);
    }
}
