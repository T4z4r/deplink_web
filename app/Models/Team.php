<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'created_by'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'team_members');
    }

    public function conversations()
    {
        return $this->hasMany(TeamConversation::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_user'); // Adjust the pivot table name if necessary
    }

    // public function users()
    // {
    //     return $this->belongsToMany(User::class);
    // }
}
