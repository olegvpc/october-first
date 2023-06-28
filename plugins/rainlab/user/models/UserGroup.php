<?php namespace RainLab\User\Models;

use ApplicationException;
use KurtJensen\Passage\Models\Key;
use KurtJensen\Passage\Models\UserGroupsKeys;
use October\Rain\Auth\Models\Group as GroupBase;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * User Group Model
 */
class UserGroup extends GroupBase
{
    const GROUP_GUEST = 'guest';
    const GROUP_REGISTERED = 'registered';

    /**
     * @var string The database table used by the model.
     */
    protected $table = 'user_groups';

    /**
     * Validation rules
     */
    public $rules = [
        'name' => 'required|between:3,64',
        'code' => 'required|regex:/^[a-zA-Z0-9_\-]+$/|unique:user_groups',
    ];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'users' => [User::class, 'table' => 'users_groups', 'timestamps' => true,],
        // 'group_keys' => [Key::class,  'timestamps' => true, 'table' => 'kurtjensen_passage_groups_keys'],
        'users_count' => [User::class, 'table' => 'users_groups', 'count' => true]
    ];

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    // public function passage_keys (): HasMany
    // {
    //     return $this->hasMany(UserGroupsKeys::class);
    // }

    protected static $guestGroup = null;

    /**
     * Returns the guest user group.
     * @return RainLab\User\Models\UserGroup
     */
    public static function getGuestGroup()
    {
        if (self::$guestGroup !== null) {
            return self::$guestGroup;
        }

        $group = self::where('code', self::GROUP_GUEST)->first() ?: false;

        return self::$guestGroup = $group;
    }
}
