# Backend Docker Usage

This Laravel API ships with its own `Dockerfile` and `docker-compose.yml`, allowing you to run the backend stack (app + MySQL) from within the `mws-backend-dev/` folder.

## Build & Run

```bash
# From mws-backend-dev/
docker compose up --build -d

# Follow logs (optional)
docker compose logs -f backend
```

The API is exposed on port `8000` by default; update the port mapping in `docker-compose.yml` if you need to change it.

## Environment

Copy `mws-backend-dev/.env.example` (if available) to `.env` and adjust values. Key settings to verify:

- `APP_URL=http://localhost:8000`
- Database credentials (must match the MySQL service)
- Sanctum/session domains and any API keys (OpenAI, Google, etc.)

Once the containers are running:

```bash
docker compose run --rm backend php artisan key:generate
docker compose run --rm backend php artisan migrate
```

Re-run migrations or additional artisan commands the same way. The `storage` volume keeps generated files and caches between restarts, and the `db-data` volume persists MySQL data locally.
