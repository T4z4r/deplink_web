<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['team_id', 'title', 'description'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }


    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
