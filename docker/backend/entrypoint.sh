#!/usr/bin/env bash
set -euo pipefail

RUNTIME_DIR="/var/www/html"
DOMAIN_DIR="/opt/domain-backend"
BOOTSTRAP_FLAG="${RUNTIME_DIR}/.airbersih_bootstrapped"

wait_for_mysql() {
  echo "Menunggu MySQL siap..."

  until mysqladmin ping \
    -h"${DB_HOST}" \
    -P"${DB_PORT}" \
    -u"${DB_USERNAME}" \
    -p"${DB_PASSWORD}" \
    --protocol=TCP \
    --ssl=0 \
    --silent; do
    echo "MySQL belum siap, mencoba lagi..."
    sleep 3
  done

  echo "MySQL siap."
}

remove_framework_migrations() {
  rm -f "${RUNTIME_DIR}/database/migrations/0001_01_01_000000_create_users_table.php"
  rm -f "${RUNTIME_DIR}/database/migrations/0001_01_01_000001_create_cache_table.php"
  rm -f "${RUNTIME_DIR}/database/migrations/0001_01_01_000002_create_jobs_table.php"
  rm -f "${RUNTIME_DIR}"/database/migrations/*create_personal_access_tokens_table.php 2>/dev/null || true
}

copy_domain_source() {
  echo "Menyalin source Air Bersih ke runtime Laravel..."

  mkdir -p "${RUNTIME_DIR}/app" "${RUNTIME_DIR}/database/migrations" "${RUNTIME_DIR}/database/seeders" "${RUNTIME_DIR}/routes" "${RUNTIME_DIR}/bootstrap"
  remove_framework_migrations

  rm -rf "${RUNTIME_DIR}/app/Http" "${RUNTIME_DIR}/app/Models"
  cp -R "${DOMAIN_DIR}/app/Http" "${RUNTIME_DIR}/app/"
  cp -R "${DOMAIN_DIR}/app/Models" "${RUNTIME_DIR}/app/"
  cp -R "${DOMAIN_DIR}/database/migrations/." "${RUNTIME_DIR}/database/migrations/"
  cp -R "${DOMAIN_DIR}/database/seeders/." "${RUNTIME_DIR}/database/seeders/"
  cp "${DOMAIN_DIR}/routes/api.php" "${RUNTIME_DIR}/routes/api.php"
  cp "${DOMAIN_DIR}/bootstrap/app.php" "${RUNTIME_DIR}/bootstrap/app.php"

}

ensure_laravel_runtime() {
  if [ ! -f "${RUNTIME_DIR}/artisan" ]; then
    echo "Inisialisasi Laravel baru di ${RUNTIME_DIR}..."
    find "${RUNTIME_DIR}" -mindepth 1 -maxdepth 1 -exec rm -rf {} +
    composer create-project laravel/laravel "${RUNTIME_DIR}" --no-scripts
  fi

  cd "${RUNTIME_DIR}"

  if ! composer show laravel/sanctum >/dev/null 2>&1; then
    echo "Menginstal Laravel Sanctum..."
    composer require laravel/sanctum --no-scripts
  else
    echo "Laravel Sanctum sudah terinstal."
  fi

  composer dump-autoload --no-scripts
  copy_domain_source

  if [ ! -f "${RUNTIME_DIR}/.env" ]; then
    cp "${RUNTIME_DIR}/.env.example" "${RUNTIME_DIR}/.env"
  fi

  php artisan key:generate --force
  php artisan config:clear || true
  php artisan route:clear || true

  touch "${BOOTSTRAP_FLAG}"
}

sync_env() {
  cd "${RUNTIME_DIR}"

  php -r '
    $envFile = ".env";
    $pairs = [
      "APP_NAME" => getenv("APP_NAME") ?: "Air Bersih Management",
      "APP_ENV" => getenv("APP_ENV") ?: "local",
      "APP_DEBUG" => getenv("APP_DEBUG") ?: "true",
      "APP_URL" => getenv("APP_URL") ?: "http://localhost:8000",
      "DB_CONNECTION" => getenv("DB_CONNECTION") ?: "mysql",
      "DB_HOST" => getenv("DB_HOST") ?: "mysql",
      "DB_PORT" => getenv("DB_PORT") ?: "3306",
      "DB_DATABASE" => getenv("DB_DATABASE") ?: "air_bersih_management",
      "DB_USERNAME" => getenv("DB_USERNAME") ?: "airbersih",
      "DB_PASSWORD" => getenv("DB_PASSWORD") ?: "airbersih123",
      "SANCTUM_STATEFUL_DOMAINS" => getenv("SANCTUM_STATEFUL_DOMAINS") ?: "localhost:5173,127.0.0.1:5173",
      "SESSION_DOMAIN" => getenv("SESSION_DOMAIN") ?: "localhost",
    ];
    $contents = file_exists($envFile) ? file_get_contents($envFile) : "";
    foreach ($pairs as $key => $value) {
      $needsQuote = preg_match("/\s/", $value) || str_contains($value, "#") || str_contains($value, "=");
      $safeValue = $needsQuote ? "\"" . addcslashes($value, "\\\"") . "\"" : $value;
      $line = $key . "=" . $safeValue;
      if (preg_match("/^" . preg_quote($key, "/") . "=.*$/m", $contents)) {
        $contents = preg_replace("/^" . preg_quote($key, "/") . "=.*$/m", $line, $contents);
      } else {
        $contents .= PHP_EOL . $line;
      }
    }
    file_put_contents($envFile, trim($contents) . PHP_EOL);
  '
}

run_migrations() {
  cd "${RUNTIME_DIR}"

  if [ "${AIRBERSIH_MIGRATE_FRESH:-false}" = "true" ]; then
    echo "Menjalankan migrate:fresh untuk reset database preview..."
    php artisan migrate:fresh --seed --force
  else
    php artisan migrate --seed --force
  fi
}

start_server() {
  cd "${RUNTIME_DIR}"
  exec php artisan serve --host=0.0.0.0 --port=8000
}

wait_for_mysql
ensure_laravel_runtime
sync_env
run_migrations
start_server
