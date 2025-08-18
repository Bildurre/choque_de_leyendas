#!/bin/bash
# Deploy script para Choque de Leyendas

echo "🚀 Iniciando deployment..."

# Verificar que estamos en main
CURRENT_BRANCH=$(git branch --show-current)
if [ "$CURRENT_BRANCH" != "main" ]; then
    echo "Error: No estás en la rama main. Actual: $CURRENT_BRANCH"
    exit 1
fi

# Confirmar cambios en main
echo "Confirmando cambios en main..."
git add .
git commit -m "Update: $(date +%Y-%m-%d_%H:%M:%S)" || echo "No hay cambios nuevos"
git push origin main

# Merge main → production
echo "Mergeando main → production..."
git checkout production
git merge main -m "Deploy: $(date +%Y-%m-%d_%H:%M:%S)"
git push origin production

# Volver a main
git checkout main

echo "Conectando al servidor..."
ssh root@68.183.2.184 << 'EOF'
cd /var/www/laravel-game-cards

echo "Actualizando código..."
git pull origin production

echo "Instalando dependencias PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "Copiando archivo de configuración si no existe..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo "Archivo .env creado desde .env.example"
fi

echo "Generando clave de aplicación..."
php artisan key:generate --force

echo "Ejecutando migraciones..."
php artisan migrate --force

echo "Limpiando cachés..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Optimizando para producción..."
php artisan config:cache
php artisan route:cache

echo "Compilando assets..."
npm ci --production=false
npm run build

echo "Configurando permisos..."
chown -R www-data:www-data /var/www/laravel-game-cards
chmod -R 755 /var/www/laravel-game-cards
chmod -R 775 /var/www/laravel-game-cards/storage
chmod -R 775 /var/www/laravel-game-cards/bootstrap/cache

echo "Reiniciando servicios..."
systemctl reload nginx
systemctl restart php8.3-fpm

echo "Deployment completado"
EOF

echo "¡Deploy finalizado con éxito!"