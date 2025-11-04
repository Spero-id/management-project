<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'unique_id',
        'no_karyawan',
        'no_quotation',
        'name',
        'email',
        'join_month',
        'join_year',
        'division_id',
        'type',
        'password',
        'ktp',
        'ijazah',
        'sertifikat',
        'sales_target',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'sertifikat' => 'array',
        ];
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'created_by');
    }

    public function quotationCount()
    {
        return $this->quotations()->count();
    }


}
