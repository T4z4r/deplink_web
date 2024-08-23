<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional if the table name is 'projects')
    protected $table = 'projects';

    // Define the fields that can be mass assigned
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'label',
        'client',
        'created_by',
    ];

    // Define relationships

    // A project is created by a user
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function requirements()
    {
        return $this->hasMany(ProjectRequirement::class);
    }
}
