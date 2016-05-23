Abstract
========

This package contains some useful add-ons and bugfixes/workarounds for using Behat-Tests in Neos projects.


## Behat and Unit/Functional Tests with million12 Docker Setup

### Install required dev dependencies

#### Install the dev packages (once)

```
composer update
cd Build/Behat && composer install
make docker-sync
```

#### Install the dev packages inside the dev-machine and restart (once):

SSH to the dev container and do the same there

```
compose update
cd Build/Behat && composer install
```

```
cd ~/Developer/Docker/dav-daz
git pull
```

Make sure that the ENV VAR `T3APP_DO_INIT_TESTS` is set to true in docker-compose.yml and then restart the web container:

```
docker-compose stop
docker-compose up -d
```

This will also re-create the Behat-Settings (Behat-Contexts and behat.yml in `*/Tests/Behavior/*`)

Make sure that `./flow help` is running ok (else, delete `Configuration/PackageStates.php` and try again)

Wait for the web container to be fully functional (`php-fpm entered RUNNING state` message in `docker-compose logs web`)

### Run Tests

Tests are executed using the [million12/php-testing:php56 Container](https://github.com/million12/docker-php-testing).
A Wrapper Shell-Script is provided for convenience (note: in the `docker` branch!)

```
cd ~/Developer/Docker/dav-daz
./tests/behat.sh
```

The Wrapper Script will also pass all shell arguments to the behat command, e.g. this will work:

(cwd is $SITE_ROOT/Tests/Behavior Directory)

```
./tests/behat.sh Features/EmptySite.feature
```

### Common Issues

#### behat: No such file or directory

Make sure to have `T3APP_DO_INIT_TESTS` ENV var set to true in your `docker-compose.yml` and to restart the containers
(see above). Else, inspect the logs from `docker-compose logs web` and check for error messages while initializing the
Behat Settings.

### Links

* http://neos.readthedocs.org/en/latest/ExtendingNeos/Tests/Behat.html#setting-up-neos-for-running-behat-tests
* https://github.com/million12/docker-typo3-flow-neos-abstract
