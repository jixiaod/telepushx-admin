<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class ActivityButton extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'tx_activity_button';
    protected $fillable = [
        'activity_id',
        'button_text',
        'button_link',
        'button_inline',
        'one_line',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'id');
    }
}

