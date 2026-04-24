<?php

namespace App\Events;

use App\Models\Listing;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ListingCreated
{
    use Dispatchable, SerializesModels;

    public Listing $listing;

    /**
     * Create a new event instance.
     */
    public function __construct(Listing $listing)
    {
        $this->listing = $listing;
    }
}
