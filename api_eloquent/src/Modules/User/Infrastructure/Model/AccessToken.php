<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Infrastructure\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $user_id
 * @property string $refresh_token
 * @property string $expired_at
 * @property int $refresh_count
 * @property string $device_info
 * @property string $created_at
 * @property string $updated_at
*/
final class AccessToken extends Model
{
    const TABLE = 'access_tokens';

    protected $table = self::TABLE;
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'user_id',
        'refresh_token',
        'expired_at',
        'refresh_count',
        'device_info',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}