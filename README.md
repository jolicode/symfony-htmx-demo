# HTMX Demo / Playground

This is the source code for the blog post at https://jolicode.com/blog/making-a-single-page-application-with-htmx-and-symfony

## Requirements

- Symfony CLI
- Docker
- PHP ^8.2

## Run the project

```shell
composer install
docker compose up -d
symfony console importmap:install
symfony console doctrine:schema:create
symfony console tailwind:build
symfony server:start -d
symfony open:local
```

Website is available on https://127.0.0.1:8000.