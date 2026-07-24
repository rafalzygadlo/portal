<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Voteable;
use App\Traits\Favoritable;
use App\Models\Category;
use App\Models\User;
use App\Models\Comment;
use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Offer extends Model
{
    use HasFactory, Voteable, Favoritable;

    protected $fillable = [
        'user_id',
        'offer_category_id',
        'title',
        'content',
        'slug',
        'price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * en: Get ALL promotions for this offer (history).
     * de: Hole ALLE Werbeaktionen für dieses Angebot (Verlauf).
     */
    public function promotions(): MorphMany
    {
        return $this->morphMany(Promotion::class, 'promotable');
    }

    /**
     * en: Relationship to get only the ACTIVE promotion.
     * de: Beziehung, um nur die AKTIVE Werbeaktion abzurufen.
     *
     * en: Returns null if none are active.
     * de: Gibt null zurück, wenn keine aktiv ist.
     */
    public function currentPromotion()
    {
        return $this->morphOne(Promotion::class, 'promotable')->where('expires_at', '>', now());
    }

    /**
     * en: A convenient method to check if the offer is currently promoted.
     * de: Eine praktische Methode, um zu prüfen, ob das Angebot aktuell beworben wird.
     */
    public function isPromoted(): bool
    {
        // en: We use `currentPromotion()` defined above. `exists()` is very efficient.
        // de: Wir verwenden die oben definierte `currentPromotion()`. `exists()` ist sehr effizient.
        return $this->currentPromotion()->exists();
    }

    public function likes()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }
}
