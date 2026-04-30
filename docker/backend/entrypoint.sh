#!/usr/bin/env bash
set -euo pipefail

RUNTIME_DIR="/var/www/html"
DOMAIN_DIR="/opt/domain-backend"
BOOTSTRAP_FLAG="${RUNTIME_DIR}/.airbersih_bootstrapped"

wait_for_mysql() {
  echo "Menunggu MySQL siap..."

  until mysqladmin ping -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USERNAME}" -p"${DB_PASSWORD}" --silent; do
    sleep 3
  done
}

prepare_laravel_runtime() {
  if [ ! -f "${RUNTIME_DIR}/artisan" ]; then
    echo "Inisialisasi Laravel baru di ${RUNTIME_DIR}..."
    rm -rf "${RUNTIME_DIR:?}/"*
    composer create-project laravel/laravel "${RUNTIME_DIR}"
  fi

  cd "${RUNTIME_DIR}"

  composer require laravel/sanctum

  cp -R "${DOMAIN_DIR}/app/Http" "${RUNTIME_DIR}/app/"
  cp -R "${DOMAIN_DIR}/app/Models" "${RUNTIME_DIR}/app/"
  cp -R "${DOMAIN_DIR}/database/migrations/." "${RUNTIME_DIR}/database/migrations/"
  cp -R "${DOMAIN_DIR}/database/seeders/." "${RUNTIME_DIR}/database/seeders/"
  cp "${DOMAIN_DIR}/routes/api.php" "${RUNTIME_DIR}/routes/api.php"
  cp "${DOMAIN_DIR}/bootstrap/app.php" "${RUNTIME_DIR}/bootstrap/app.php"

  if [ ! -f "${RUNTIME_DIR}/.env" ]; then
    cp "${RUNTIME_DIR}/.env.example" "${RUNTIME_DIR}/.env"
  fi

  php artisan vendor:publish --provider="Laravel\\Sanctum\\SanctumServiceProvider" --force || true
  php artisan key:generate --force
  php artisan config:clear
  php artisan route:clear

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
      "APP_URL" => getenv("APP_URL") ?: "http://localhost",
      "DB_CONNECTION" => getenv("DB_CONNECTION") ?: "mysql",
      "DB_HOST" => getenv("DB_HOST") ?: "mysql",
      "DB_PORT" => getenv("DB_PORT") ?: "3306",
      "DB_DATABASE" => getenv("DB_DATABASE") ?: "air_bersih_management",
      "DB_USERNAME" => getenv("DB_USERNAME") ?: "airbersih",
      "DB_PASSWORD" => getenv("DB_PASSWORD") ?: "airbersih123",
      "SANCTUM_STATEFUL_DOMAINS" => getenv("SANCTUM_STATEFUL_DOMAINS") ?: "localhost,127.0.0.1",
      "SESSION_DOMAIN" => getenv("SESSION_DOMAIN") ?: "localhost",
    ];
    $contents = file_exists($envFile) ? file_get_contents($envFile) : "";
    foreach ($pairs as $key => $value) {
      $line = $key . "=" . $value;
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
  php artisan migrate --seed --force
}

start_server() {
  cd "${RUNTIME_DIR}"
  exec php artisan serve --host=0.0.0.0 --port=8000
}

wait_for_mysql

if [ ! -f "${BOOTSTRAP_FLAG}" ]; then
  prepare_laravel_runtime
fi

sync_env
run_migrations
start_server
