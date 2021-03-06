## GMail Account Log Bundle ##

- Add bundle to AppKernel.php
````
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            ...
            new SAS\IRAD\GmailAccountLogBundle\GmailAccountLogBundle(),
            ...
````

- Update parameters.yml with your account log database parameters:
````
    database_driver: pdo_mysql
    database_host: *host*
    database_port: ~
    database_name: *schema*
    database_user: *username*
    database_password: *password*
````
- Also update parameters.yml with users who will have the role of "log_admin":
````
    admin_users:
        ROLE_TOKEN_ADMIN: [ ... ]
        ROLE_LOG_ADMIN:   [ logadmin1, logadmin2, etc... ]
````

- Add routing for web admin pages to app/config/routing.yml:
````
account_log:
    resource: "@GmailAccountLogBundle/Controller/"
    type:     annotation
    prefix:   /admin/accountLog
````

- The default time zone for account logging is Eastern Daylight Time. You may override
by adding the following settings in your parameters.yml file:
`````
parameters:
    account_logger:
        timezone:     "America/New_York"
`````