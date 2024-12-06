<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static create(array $addedCity)
 * @method static where(string $string, $Id)
 * @method static find(mixed $Id)
 */
class City extends Model
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
        'CountryId',
        'createdAt',
        'updatedAt'
    ];

    protected $casts = [
        'Id' => 'string',
    ];

    public $timestamps = true;

    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'CountryId', 'Id');
    }

    public function city(): HasMany
    {
        return $this->hasMany(District::class);
    }

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
