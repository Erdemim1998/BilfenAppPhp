<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static select(array $array)
 * @method static where(string $string, $id)
 * @method static create(array $addedCountry)
 * @method static find(mixed $Id)
 */
class Country extends Model
{
    protected $primaryKey = 'Id';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'Id',
        'Name',
        'createdAt',
        'updatedAt'
    ];

    protected $casts = [
        'Id' => 'string',
    ];

    public $timestamps = true;

    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    public function city(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
