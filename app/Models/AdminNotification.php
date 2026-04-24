<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AdminNotification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'icon',
        'color',
        'notifiable_type',
        'notifiable_id',
        'action_url',
        'read',
        'read_at',
    ];

    protected $casts = [
        'read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Relation polymorphique avec les modèles notifiables
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
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
     * Scope pour les notifications non lues
     */
    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    /**
     * Scope pour les notifications récentes
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }
}
