<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'user_id',
        'listing_id',
        'name',
        'email',
        'phone',
        'message',
        'read_at',
        'admin_response',
        'response_sent_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'response_sent_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le listing
     */
    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    /**
     * Vérifier si le message a été lu
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Vérifier si une réponse a été envoyée
     */
    public function hasResponse(): bool
    {
        return $this->admin_response !== null && $this->response_sent_at !== null;
    }
}
