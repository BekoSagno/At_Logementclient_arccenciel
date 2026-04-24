<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'currency',
        'type',
        'service_status',
        'status',
        'is_available',
        'published_at',
        'scheduled_at',
        'images',
        'address',
        'city',
        'is_featured',
        'bedrooms',
        'bathrooms',
        'surface',
        'document_type',
        'amenities',
        'social_links',
        'custom_fields',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'is_available' => 'boolean',
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'price' => 'decimal:2',
        'images' => 'array',
        'amenities' => 'array',
        'social_links' => 'array',
        'custom_fields' => 'array',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'surface' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($listing) {
            if (empty($listing->slug)) {
                $listing->slug = Str::slug($listing->title);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('status', true)
                    ->where('is_available', true) // Seulement les annonces disponibles
                    ->where(function ($q) {
                        $q->where(function ($subQ) {
                            $subQ->whereNotNull('published_at')
                                 ->where('published_at', '<=', now());
                        })->orWhere(function ($subQ) {
                            $subQ->whereNotNull('scheduled_at')
                                 ->where('scheduled_at', '<=', now())
                                 ->whereNull('published_at');
                        });
                    });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get the thumbnail image (first image from the images array)
     */
    public function getThumbnailAttribute(): ?string
    {
        if (empty($this->images) || !is_array($this->images)) {
            return null;
        }

        return $this->images[0] ?? null;
    }

    /**
     * Relation avec les messages
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Relation avec l'historique
     */
    public function history(): HasMany
    {
        return $this->hasMany(ListingHistory::class);
    }

    /**
     * Relation avec les favoris
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }
}
