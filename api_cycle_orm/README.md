# Shop API

```bash
composer test
```

Site console
----
run console command

```bash
php cli app:test
```

Generate api docs
---
```bash
php cli open-api:generate-docs
```


Migration
----

Generate a migration by comparing your current database to your mapping information.

```bash
php doctrine migrations:diff
```

run migration

```bash
php doctrine migrations:migrate
```

run migration for sh command without questions

```bash
php doctrine migrations:migrate --no-interaction
```

ORM
----

You can update DB without migrations

```bash
php doctrine orm:schema-tool:update --force
```