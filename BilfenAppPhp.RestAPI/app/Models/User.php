<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static create(array $addedUser)
 * @method static where(string $string, mixed $id)
 * @method static find(mixed $Id)
 */
class User extends Model
{
    use HasFactory;

    protected $primaryKey = 'Id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'FirstName',
        'LastName',
        'UserName',
        'Email',
        'Password',
        'PasswordHash',
        'createdAt',
        'updatedAt',
        'RoleId',
        'ImagePath',
        'TCKN',
        'MotherName',
        'FatherName',
        'BirthDate',
        'Gender',
        'CivilStatus',
        'EmploymentDate',
        'MilitaryStatus',
        'PostponementDate',
        'CountryId',
        'CityId',
        'DistrictId',
        'Address'
    ];

    public $timestamps = true;

    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'RoleId', 'Id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'CountryId', 'Id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'CityId', 'Id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'DistrictId', 'Id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
