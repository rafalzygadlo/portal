<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;

use App\Models\Business;
use App\Models\Offer;
use App\Models\Article;
use App\Models\Poll\Poll;
use App\Models\Comment;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'avatar',
        'password',
        'user_type',
        'subdomain',
        'credits',
        'referral_code',
        'referred_by_user_id',
        'welcome_bonus_received',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'credits' => 'integer',
        'welcome_bonus_received' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $user) {
            if (empty($user->referral_code)) {
                $user->referral_code = $user->generateReferralCode();
            }

            if ($user->welcome_bonus_received === null) {
                $user->welcome_bonus_received = false;
            }
        });
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function referredBy(): BelongsTo
    {
        return $this->belongsTo(self::class, 'referred_by_user_id');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(self::class, 'referred_by_user_id');
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class);
    }

    public function generateReferralCode(): string
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (self::query()->where('referral_code', $code)->exists());

        return $code;
    }

    public function addCredits(int $amount, string $type = 'manual', ?string $description = null, $relatedModel = null): int
    {
        if ($amount <= 0) {
            return (int) $this->credits;
        }

        $this->increment('credits', $amount);

        $this->creditTransactions()->create([
            'user_id' => $this->id,
            'type' => $type,
            'amount' => $amount,
            'description' => $description ?? 'Dodano kredyty',
            'related_type' => $relatedModel ? get_class($relatedModel) : null,
            'related_id' => $relatedModel?->id,
        ]);

        return (int) $this->fresh()->credits;
    }

    public function spendCredits(int $amount, string $type = 'promotion', ?string $description = null, $relatedModel = null): bool
    {
        if ($amount <= 0) {
            return true;
        }

        if ($this->credits < $amount) {
            return false;
        }

        $this->decrement('credits', $amount);

        $this->creditTransactions()->create([
            'user_id' => $this->id,
            'type' => $type,
            'amount' => -$amount,
            'description' => $description ?? 'Wydano kredyty',
            'related_type' => $relatedModel ? get_class($relatedModel) : null,
            'related_id' => $relatedModel?->id,
        ]);

        return true;
    }

    public function applyReferralCode(string $code): bool
    {
        $code = trim(strtoupper($code));

        if ($code === '' || $this->referred_by_user_id !== null || $this->referral_code === $code) {
            return false;
        }

        $referrer = self::query()->where('referral_code', $code)->first();

        if (! $referrer || $referrer->id === $this->id) {
            return false;
        }

        $this->referred_by_user_id = $referrer->id;
        $this->save();

        $referrer->addCredits(50, 'referral', 'Polecenie znajomego: ' . $this->email, $this);

        return true;
    }

    public function grantWelcomeBonusIfNeeded(): bool
    {
        if ($this->welcome_bonus_received) {
            return false;
        }

        $this->welcome_bonus_received = true;
        $this->save();
        $this->addCredits(50, 'welcome_bonus', 'Bonus powitalny za pierwsze logowanie', $this);

        return true;
    }
    
    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany(Business::class, 'business_user');
    }

    public function ownedBusinesses(): BelongsToMany
    {
        return $this->businesses()->wherePivot('owner', true);
    }


    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class);
    }   
    
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function polls(): HasMany
    {
        return $this->hasMany(Poll::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

}
