# Main commands for Symfony

Copyright (c) Mikita Tsybulka

---

### Docker

Run docker compose to build fresh images
```bash
build --no-cache 
```

Run to set up and start a fresh Symfony project
```bash
docker compose up --pull always -d --wait
```

Stop containers
```bash
docker compose stop
```

Resume stoped containers
```bash
docker compose up -d
```

### Migrations

Make migration

```bash
php bin/console make:migration
```

Load migration

```bash
php bin/console doctrine:migrations:migrate
```

### Fixtures

Pre-condition is to install orm-fixtures

```bash
composer require orm-fixtures --dev
```

Make Fixture

```bash
php bin/console make:fixture <Entiry>Fixtures
```

Load fixtures to database (Before loafing **restart php container**)

```bash
php bin/console doctrine:fixtures:load
```
or delete all data before and load.

```bash
php bin/console doctrine:fixtures:load --append
```
---
