<?php
namespace Djbarnes\VerifyLdapL4\Models;

class Permission extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array('name', 'description');

    /**
     * Roles
     *
     * @return object
     */
    public function roles()
    {
        return $this->belongsToMany(
                'Djbarnes\VerifyLdapL4\Models\Role',
                $this->prefix.'permission_role'
            )
        ->withTimestamps();
    }
}