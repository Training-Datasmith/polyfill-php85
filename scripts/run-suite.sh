#!/usr/bin/env bash
set -euo pipefail

# Digest-pinned PHP CLI images (amd64 manifest digests from Docker Hub).
PHP_72_IMAGE='docker.io/library/php:7.2.34-cli@sha256:42ffbc0798e4449bbd1e14fc4dcb87774aa1ad1900a09ef6a965bc0880aa2161'
PHP_74_IMAGE='docker.io/library/php:7.4.33-cli@sha256:620a6b9f4d4feef2210026172570465e9d0c1de79766418d3affd09190a7fda5'
PHP_80_IMAGE='docker.io/library/php:8.0.30-cli@sha256:0569e384b9064c04dec55dc6e41be41b494a878dfbb6577a7d76bd50cfd5bc00'
PHP_85_IMAGE='docker.io/library/php:8.5-cli@sha256:01a109229f4465bc9ef042d9198f09a4d9da7775a825dbd8572dcdbd8756c4d3'

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
RANDOM_SEED=20261008

if command -v podman >/dev/null 2>&1; then
  CONTAINER=podman
elif command -v docker >/dev/null 2>&1; then
  CONTAINER=docker
else
  echo "Neither podman nor docker is available." >&2
  exit 1
fi

install_vendor() {
  local image="$1"
  local label="$2"

  ${CONTAINER} run --rm --network=host -v "${ROOT}:/app:Z" -w /app "${image}" bash -lc "
    set -euo pipefail
    export DEBIAN_FRONTEND=noninteractive
    if command -v apt-get >/dev/null 2>&1; then
      echo 'Acquire::Check-Valid-Until \"false\";' > /etc/apt/apt.conf.d/99no-check-valid
      if grep -q buster /etc/os-release 2>/dev/null; then
        sed -i 's|deb.debian.org|archive.debian.org|g' /etc/apt/sources.list
        sed -i 's|security.debian.org|archive.debian.org|g' /etc/apt/sources.list
      fi
      if grep -q bullseye /etc/os-release 2>/dev/null; then
        sed -i 's|deb.debian.org/debian-security|archive.debian.org/debian-security|g' /etc/apt/sources.list
        sed -i 's|security.debian.org/debian-security|archive.debian.org/debian-security|g' /etc/apt/sources.list
      fi
      apt-get update -qq
      apt-get install -y -qq --no-install-recommends git unzip libzip-dev libxml2-dev
      docker-php-ext-install -j\"\$(nproc)\" zip 2>/dev/null || true
    fi
    php -r \"copy('https://getcomposer.org/installer', 'composer-setup.php');\"
    php -r \"copy('https://composer.github.io/installer.sig', 'composer-setup.sig');\"
    php -r \"if (hash_file('sha384', 'composer-setup.php') !== trim(file_get_contents('composer-setup.sig'))) { fwrite(STDERR, 'Composer installer checksum mismatch'.PHP_EOL); exit(1); }\"
    php composer-setup.php --version=2.2.25 --install-dir=/usr/local/bin --filename=composer
    rm -f composer-setup.php composer-setup.sig
    composer update --no-interaction --prefer-dist
    echo '--- vendor install ${label} ---'
  "
}

run_phpunit() {
  local image="$1"
  local phpunit_args="$2"
  local label="$3"

  ${CONTAINER} run --rm -v "${ROOT}:/app:Z" -w /app "${image}" bash -lc "
    set -euo pipefail
    echo '--- ${label} ---'
    vendor/bin/phpunit ${phpunit_args}
  "
}

echo '--- PHP 7.4 stubs74/NoDiscard.php lint ---'
${CONTAINER} run --rm -v "${ROOT}:/app:Z" "${PHP_74_IMAGE}" php -l /app/Resources/stubs74/NoDiscard.php

install_vendor "${PHP_72_IMAGE}" 'php7.2'
for i in 1 2; do
  run_phpunit "${PHP_72_IMAGE}" "--testsuite default --colors=never" "php7.2 default run ${i}"
done
for i in 1 2; do
  run_phpunit "${PHP_72_IMAGE}" "--testsuite default --order-by=random --random-order-seed=${RANDOM_SEED} --colors=never" "php7.2 random run ${i}"
done

install_vendor "${PHP_74_IMAGE}" 'php7.4'
for i in 1 2; do
  run_phpunit "${PHP_74_IMAGE}" "--testsuite default --colors=never" "php7.4 default run ${i}"
done
for i in 1 2; do
  run_phpunit "${PHP_74_IMAGE}" "--testsuite default --order-by=random --random-order-seed=${RANDOM_SEED} --colors=never" "php7.4 random run ${i}"
done

install_vendor "${PHP_80_IMAGE}" 'php8.0'
for i in 1 2; do
  run_phpunit "${PHP_80_IMAGE}" "--colors=never" "php8.0 default run ${i}"
done
for i in 1 2; do
  run_phpunit "${PHP_80_IMAGE}" "--order-by=random --random-order-seed=${RANDOM_SEED} --colors=never" "php8.0 random run ${i}"
done

echo '--- php8.5 native class guard ---'
${CONTAINER} run --rm -v "${ROOT}:/app:Z" -w /app "${PHP_85_IMAGE}" php -r "
require 'vendor/autoload.php';
if (!(new ReflectionClass('NoDiscard'))->isInternal()) { fwrite(STDERR, 'NoDiscard is not native'.PHP_EOL); exit(1); }
if (!(new ReflectionClass('DelayedTargetValidation'))->isInternal()) { fwrite(STDERR, 'DelayedTargetValidation is not native'.PHP_EOL); exit(1); }
if (!(new ReflectionFunction('array_first'))->isInternal()) { fwrite(STDERR, 'array_first is not native'.PHP_EOL); exit(1); }
echo 'php8.5 native guard ok'.PHP_EOL;
"
