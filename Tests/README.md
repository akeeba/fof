# Running tests
In order to run the tests, you have to configure your environment:

 1. Create a database for the tests
 2. Install required libraries with Composer

### Create a database for the tests

We need a database where we will create all the tables required by the tests. 
The best thing is to provide an empty database, the test suite will create all the needed tables.

Once created, you have to copy the file `Tests/config.dist.php`, rename it to `Tests/config.php` and update its contents to point to the created database.

For local testing (not using Travis CI) you need to put the latest Joomla! staging files somewhere and update the `site_root` line in your `Tests/config.php` file to point there. You do not need to install Joomla on that folder, this is automatically detected and done as part of the tests.

### Install required libraries with Composer

You simply have to run `composer install` in order to install all the required libraries. If you want to update the composer dependencies, run `composer update`. Composer is not bundled in the repo.

### How to run tests

From the `Tests` folder run
```bash
../vendor/bin/phpunit -c ../phpunit.xml
```
