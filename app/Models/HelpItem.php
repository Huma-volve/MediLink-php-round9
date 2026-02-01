<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpItem extends Model
{

    protected $fillable = [
        'id',
        'faq_url',
        'contact_support_url',
        'documentation_url',
        'video_tutorials_url',
    ];

}
