<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prospect extends Model
{


    // NOTE: Pastikan is_empty nya
    protected $fillable = [
        'customer_name',
        'no_handphone',
        'email',
        'company',
        'company_identity',
        'status_id',
        'target_from_month',
        'target_to_month',
        'target_from_year',
        'target_to_year',
        'note',
        'pre_sales',
        'document',
        'po_file',
        'spk_file',
        'created_by',
        'is_empty',
        'product_offered',
        'is_converted_to_project',
    ];

    protected $appends = ['target_deal'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function minuteOfMeetings()
    {
        return $this->morphOne(MinuteOfMeeting::class, 'noteable');
    }

    public function preSalesPerson()
    {
        return $this->belongsTo(User::class, 'pre_sales');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function logs()
    {
        return $this->hasMany(ProspectLog::class)->orderBy('created_at', 'desc')->with(['user', 'fromStatus', 'toStatus']);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function prospectStatus()
    {
        return $this->belongsTo(ProspectStatus::class, 'status_id');
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'prospect_id');
    }

    public function getTargetDealAttribute()
    {
        $months = [
            '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
            '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug',
            '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec',
        ];

        $fromMonth = $months[$this->target_from_month] ?? $this->target_from_month;
        $toMonth = $months[$this->target_to_month] ?? $this->target_to_month;
        $fromYear = $this->target_from_year ?? $this->target_from_year;
        $toYear = $this->target_to_year ?? $this->target_to_year;

        if ($fromMonth && $toMonth && $fromYear && $toYear) {
            if ($fromYear == $toYear) {
                return "{$fromMonth} - {$toMonth} {$fromYear}";
            } else {
                return "{$fromMonth} {$fromYear} - {$toMonth} {$toYear}";
            }
        }

        return $this->target_from_year ?? '';
    }

    // Prospect -> Project conversion logic moved to controller
}
