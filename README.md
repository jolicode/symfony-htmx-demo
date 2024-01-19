# Starting the project

This is the source code for the blog post at https://jolicode.com/blog/making-a-single-page-application-with-htmx-and-symfony

## Requirements

- Symfony CLI
- Docker
- PHP ^8.2

## Installation

- Clone the repository

```shell
composer install
```

- Edit your `.env` and your `docker-compose.yaml` files to add your database credentials

## Run the project

```shell
docker compose up -d
symfony server:start -d
symfony open:local
```

- 🌐: `https://127.0.0.1:8000`
