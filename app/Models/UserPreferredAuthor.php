<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreferredAuthor extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'author_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
