<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasDateTimeFormatter;
    protected $table = 'tx_activity';

    public function buttons()
    {
        return $this->hasMany(ActivityButton::class, 'activity_id');
    }

}

