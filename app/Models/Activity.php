<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $key
 * @property $participants
 * @property $price
 * @property $type
 * @property $activity
 * @property $link
 * @property $accessibility
 * @property $loaded_at
 */
class Activity extends Model
{
    use HasFactory, HasAttributes;

    public const FILTER_PROPERTIES = ['participant' => 'participants', 'price', 'type'];

    public function getTable(): string
    {
        return 'activities';
    }

    public function populateFromJson($json)
    {
        foreach ($json as $propKey => $propValue) {
            $this->$propKey = $propValue;
        }
    }
}
