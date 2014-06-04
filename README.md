GMail Account Log Bundle

1. Add bundle to AppKernel.php
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

2. Update parameters.yml with your account log database parameters:

````
    database_driver: pdo_mysql
    database_host: _host_
    database_port: ~
    database_name: _schema_
    database_user: _username_
    database_password: _password_
````