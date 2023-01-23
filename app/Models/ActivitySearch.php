<?php /** @noinspection SpellCheckingInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Database\Eloquent\Model;

class ActivitySearch extends Model
{
    use HasAttributes;

    protected $fillable = [
        'onPage',
        'participant',
        'price',
        'type',
    ];
}
