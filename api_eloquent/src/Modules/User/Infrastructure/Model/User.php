<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Infrastructure\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpLab\Modules\User\Domain\Entity\Status;

/**
 * @property int $id
 * @property string $role
 * @property string $username
 * @property string $password_hash
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @method static UserBuilder query()
 */
final class User extends Model
{
    use SoftDeletes;

    const TABLE = 'users';

    protected $table = self::TABLE;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'username',
        'password_hash',
        'role',
        'status',
    ];

    public function accessTokens(): HasMany
    {
        return $this->hasMany(AccessToken::class, 'user_id', 'id');
    }

    public function newEloquentBuilder($query)
    {
        return (new UserBuilder($query));
    }
}

class UserBuilder extends Builder
{
    public function active(): self
    {
        return $this->where('status', Status::STATUS_ACTIVE);
    }

    public function filterByUsername($value = null): self
    {
        return !is_null($value) ?
                $this->where('username', 'LIKE', "%$value%") :
                $this;
    }

    public function filterByRole($value = null): self
    {
        return !is_null($value) ?
            $this->where('role', '=', $value) :
            $this;
    }

    public function filterByStatus($value = null): self
    {
        return !is_null($value) ?
            $this->where('status', '=', $value) :
            $this;
    }
}