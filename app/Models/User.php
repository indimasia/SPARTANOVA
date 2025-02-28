<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\SosialMediaAccount;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\PushNotification;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use NotificationChannels\WebPush\HasPushSubscriptions;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasPushSubscriptions;
    // HasPushSubscriptions
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
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
        'company',
        'latitude',
        'longitude',
        'current_latitude',
        'current_longitude',
        'login_count',
        'referral_code',
        'referred_by',
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

    public function topups()
    {
        return $this->hasMany(Transaction::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function userPerformance()
    {
        return $this->hasOne(UserPerformance::class);
    }

    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by', 'referral_code');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by', 'referral_code');
    }

    public static function getUserLocation($latitude, $longitude)
    {
        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&zoom=18&addressdetails=1";
        $response = Http::withHeaders([
            'User-Agent' => config('app.name'),
        ])->get($url);
        return $response->json();
    }

    public function getSocialMediaUsername($platform)
    {
        return $this->sosialMediaAccounts->where('sosial_media', $platform)->first()?->account ?? 'Tidak punya akun';
    }

    public function sendPushNotification($title, $body, $url)
    {
        $user = User::find(3); // Ganti dengan ID user yang ingin dikirim notifikasi
$user->notify(new PushNotification());
    }

    public function updatePushSubscription($endpoint, $keys)
    {
        $this->pushSubscriptions()->updateOrCreate(
            ['endpoint' => $endpoint],
            ['public_key' => $keys['p256dh'], 'auth_token' => $keys['auth'], 'content_encoding' => 'aesgcm']
        );
    }

}
