<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static create(array $array)
 * @method static find(mixed $Id)
 * @method static where(string $string, $id)
 */
class District extends Model
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
        'CityId',
        'createdAt',
        'updatedAt'
    ];

    protected $casts = [
        'Id' => 'string',
    ];

    public $timestamps = true;

    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'CityId', 'Id');
    }

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
