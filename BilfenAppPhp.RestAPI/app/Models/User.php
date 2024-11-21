<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $addedUser)
 * @method static where(string $string, mixed $id)
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
        'RoleId'
    ];

    public $timestamps = true;

    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    public function role()
    {
        return $this->belongsTo(Role::class, 'RoleId', 'Id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
