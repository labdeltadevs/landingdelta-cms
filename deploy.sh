#!/usr/bin/env bash

# ===============================================================
# Auto Deploy Script For DELTA CMS
# ===============================================================

set -e

# ------------------------ Definiciones -------------------------

REPO_DIR="/home/u2499-actzgs2pbre2/www/laboratoriosdelta.net"

LOG_DIR="$REPO_DIR/storage/logs"
LOG_FILE="$LOG_DIR/deploy.log"

mkdir -p "$LOG_DIR"

# ------------------------ Funciones ----------------------------

log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

run() {
    log "Iniciando: $1"

    shift

    "$@" >>"$LOG_FILE" 2>&1

    log "Finalizado: $1"
}

# ------------------------ Inicio -------------------------------

log "============================================================"
log "Inicio de despliegue"

cd "$REPO_DIR"

# ------------------------ Deploy -------------------------------

run "Git Fetch" \
    git fetch origin main

run "Git Reset" \
    git reset --hard origin/main

run "Composer Install" \
    composer install --no-dev --optimize-autoloader

# run "NPM CI" \
#     npm ci

# Descomentar si aplica
# run "NPM Build" \
#     npm run build

run "Migraciones" \
    php artisan migrate --force

run "Permisos (backfill idempotente)" \
    #php artisan db:seed --class=RolePermissionSeeder --force

# Descomentar si aplica
# run "Seeders" \
#     php artisan db:seed --force

#
#run "Config Cache" \
#    php artisan config:cache
#
#run "Route Cache" \
#    php artisan route:cache
#
#run "View Cache" \
#    php artisan view:cache
#
#run "Event Cache" \
#    php artisan event:cache

run "Clear Cache" \
    php artisan optimize:clear

run "Storage Link" \
    php artisan storage:link

run "Permissions" \
    chmod -R 775 "$REPO_DIR/storage" "$REPO_DIR/bootstrap/cache"

run "Make Cache" \
    php artisan optimize

# Descomentar si se utilizan colas
# run "Queue Restart" \
#     php artisan queue:restart

log "Deploy concluido correctamente."
log "============================================================"
echo ""
