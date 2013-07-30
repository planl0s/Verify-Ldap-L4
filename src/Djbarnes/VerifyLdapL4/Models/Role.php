<?php
namespace Djbarnes\VerifyLdapL4\Models;

class Role extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array('name', 'description', 'level');

    /**
     * Users
     *
     * @return object
     */
    public function users()
    {
        return $this->belongsToMany(
                'Djbarnes\VerifyLdapL4\Models\User',
                $this->prefix.'role_user'
            )
        ->withTimestamps();
    }

    /**
     * Permissions
     *
     * @return object
     */
    public function permissions()
    {
        return $this->belongsToMany(
                'Djbarnes\VerifyLdapL4\Models\Permission',
                $this->prefix.'permission_role'
            )
        ->withTimestamps();
    }
}