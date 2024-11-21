<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $id)
 * @method static select(string $string, string $string1, \Illuminate\Contracts\Database\Query\Expression $raw, \Illuminate\Contracts\Database\Query\Expression $raw1)
 * @method static create(array $array)
 * @method static find(mixed $Id)
 */
class Role extends Model
{
    use HasFactory;

    protected $primaryKey = 'Id';

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

    public $timestamps = true;

    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
