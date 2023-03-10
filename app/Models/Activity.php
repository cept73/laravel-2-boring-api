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

    public static function getWhereConditions($data): array
    {
        $where = [];

        foreach (self::FILTER_PROPERTIES as $filterKey => $dbProperty) {
            if (is_int($filterKey)) {
                $filterKey = $dbProperty;
            }
            if ($filterValue = $data[$filterKey] ?? null) {
                $where[$dbProperty] = $filterValue;
            }
        }

        return $where;
    }
}
