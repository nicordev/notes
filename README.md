# notes

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nicordev/notes/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nicordev/notes/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/nicordev/notes/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/nicordev/notes/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/nicordev/notes/badges/build.png?b=master)](https://scrutinizer-ci.com/g/nicordev/notes/build-status/master) [![Code Intelligence Status](https://scrutinizer-ci.com/g/nicordev/notes/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

A website to manage notes.

## Installation

1. Clone the project with `git clone urlOfTheGitHubRepositoryHere`
1. Run `composer install`
1. Create a database and create a `.env.local` file with your database credentials with the following content:
    ```
    ###> symfony/framework-bundle ###
    APP_ENV=prod
    APP_SECRET=yourOwnSecretCode
    ###< symfony/framework-bundle ###

    ###> doctrine/doctrine-bundle ###
    DATABASE_URL=mysql://yourDatabaseUserNameHere:yourDatabaseUserPasswordHere@127.0.0.1:3306/yourDatabaseNameHere
    ###< doctrine/doctrine-bundle ###
    ```
1. Run `php bin/console doctrine:schema:update --force` to create the right tables
1. Optional: Run `php bin/console hautelook:fixtures:load` to load some demo data
