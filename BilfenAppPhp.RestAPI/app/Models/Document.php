<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static find(mixed $Id)
 * @method static where(string $string, $id)
 */
class Document extends Model
{
    use HasFactory;

    protected $primaryKey = 'Id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'Name',
        'FilePath',
        'Status',
        'createdAt',
        'updatedAt',
        'UserId'
    ];

    public $timestamps = true;

    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId', 'Id');
    }
}
