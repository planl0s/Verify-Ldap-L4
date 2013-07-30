<?php namespace Djbarnes\VerifyLdapL4;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Guard;

class VerifyLdapL4ServiceProvider extends ServiceProvider 
{
  /**
   * Indicates if loading of the provider is deferred.
   *
   * @var bool
   */
  protected $defer = false;

  /**
   * Bootstrap the application events.
   *
   * @return void
   */
  public function boot()
  {
    $this->package('djbarnes/verify-ldap-l4');

    \Auth::extend('verify-ldap-l4', function()
    {
      return new Guard(
        new VerifyUserProvider(
          \Config::get('auth.model'),
          \Config::get('verify-ldap-l4::ldap_server')
        ),
        \App::make('session')
      );
    });
  }

  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides()
  {
    return array();
  }
}