# Starting the project

This is the source code for the blog post at https://jolicode.com/blog/making-a-single-page-application-with-htmx-and-symfony

## Requirements

- Symfony CLI
- PHP 8.2+ (with appropriate database extensions, sqlite by default)

## Installation

- Clone the repository

```shell
composer install
bin/console doctrine:database:create 
bin/console doctrine:schema:update --force --complete
symfony server:start -d
symfony open:local
```
- 
- 🌐: `https://127.0.0.1:8000`

## Production

When deploying to production, use postgres or mysql and change the environment variables accordingly.



