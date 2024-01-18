# Starting the project

## Requirements

- Symfony CLI
- Docker

## Installation

- Clone the repository

```shell
composer install
php bin/console tailwind:build
```

- Edit your `.env` and your `docker-compose.yaml` files to add your database credentials

## Run the project

```shell
docker compose up -d
symfony server:start -d
symfony open:local
```

- 🌐: `https://127.0.0.1:8000`
