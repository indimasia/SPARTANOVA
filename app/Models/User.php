<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\SosialMediaAccount;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'date_of_birth',
        'generation_category',
        'phone',
        'village_kode',
        'district_kode',
        'regency_kode',
        'province_kode',
        'interest',
        'contact_wa',
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
            'date_of_birth' => 'date',
            'interest' => 'array',
            // 'gender' => 'enum',
        ];
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return ($this->hasAnyRole(['admin', 'pengiklan']));
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_kode', 'kode');
    }

    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class, 'regency_kode', 'kode');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_kode', 'kode');
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'village_kode', 'kode');
    }

    public function sosialMediaAccounts(): HasMany
    {
        return $this->hasMany(SosialMediaAccount::class);
    }

    public function jobParticipants(): HasMany
    {
        return $this->hasMany(JobParticipant::class);
    }

    public function createdJobs(): HasMany
    {
        return $this->hasMany(JobCampaign::class, 'created_by');
    }
}
