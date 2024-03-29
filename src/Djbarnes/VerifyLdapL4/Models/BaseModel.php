<?php
namespace Djbarnes\VerifyLdapL4\Models;

class BaseModel extends \Eloquent
{
    /**
     * Table prefix
     *
     * @var string
     */
    protected $prefix = '';

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        // Set the prefix
        $this->prefix = \Config::get('verify-ldap-l4::prefix', '');

        $this->table = $this->prefix.$this->getTable();
    }
}