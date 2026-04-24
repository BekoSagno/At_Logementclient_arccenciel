<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read',
        'read_at',
        'email_sent',
        'email_sent_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read' => 'boolean',
        'read_at' => 'datetime',
        'email_sent' => 'boolean',
        'email_sent_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Marquer comme lu
     */
    public function markAsRead(): void
    {
        if (!$this->read) {
            $this->update([
                'read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Marquer l'email comme envoyé
     */
    public function markEmailAsSent(): void
    {
        if (!$this->email_sent) {
            $this->update([
                'email_sent' => true,
                'email_sent_at' => now(),
            ]);
        }
    }
}
