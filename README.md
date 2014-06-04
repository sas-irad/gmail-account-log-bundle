## GMail Account Log Bundle ##

- Add bundle to AppKernel.php
````
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            ...
            new Penn\GmailAccountLogBundle\GmailAccountLogBundle(),
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