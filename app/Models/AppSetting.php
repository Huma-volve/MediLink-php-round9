<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{

    protected $fillable = [
        'id',
        'app_name',
        'app_version',
        'company_name',
        'terms_url',
        'privacy_url',
        'license_url',
        'release_notes_url',
        'support_email',
        'website_url',
        'company_address',
        'app_logo',
    ];
}
