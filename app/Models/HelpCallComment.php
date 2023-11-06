<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpCallComment extends Model
{
    use HasFactory;

    protected $table = 'help_call_comments';

    protected $fillable = [
        'content',
        'help_call_id',
        'owner_id',
    ];
}
