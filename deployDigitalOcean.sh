#!/bin/bash
# Deploy script para Choque de Leyendas - DigitalOcean
# SIN CACHE DE RUTAS (por Laravel Localization)

# Colores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${GREEN}🚀 Iniciando deployment...${NC}"

# Verificar rama
CURRENT_BRANCH=$(git branch --show-current)
if [ "$CURRENT_BRANCH" != "main" ]; then
    echo -e "${RED}❌ Error: No estás en main. Actual: $CURRENT_BRANCH${NC}"
    exit 1
fi

# Push cambios
echo -e "${YELLOW}📝 Subiendo cambios...${NC}"
git add .
git commit -m "Deploy: $(date +%Y-%m-%d_%H:%M:%S)" || true
git push origin main

# Merge a production
git checkout production
git merge main -m "Deploy: $(date +%Y-%m-%d_%H:%M:%S)"
git push origin production
git checkout main

# Deploy en servidor
echo -e "${YELLOW}🌐 Desplegando en servidor...${NC}"
ssh root@68.183.2.184 << 'DEPLOY'
set -e

cd /var/www/laravel-game-cards

echo "📥 Actualizando código..."
git fetch origin production
git reset --hard origin/production

echo "📦 Instalando dependencias..."
composer install --no-dev --optimize-autoloader --no-interaction
npm ci --production=false

echo "🎨 Compilando assets..."
npm run build

echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

echo "🧹 Limpiando TODOS los cachés..."
php artisan config:clear
php artisan route:clear    # IMPORTANTE: Limpiar rutas
php artisan view:clear
php artisan cache:clear

echo "⚡ Optimizando (SIN cachear rutas)..."
# Cachear configuración
php artisan config:cache

# NO CACHEAR RUTAS - Comentado por incompatibilidad con Laravel Localization
# php artisan route:cache

# Cachear vistas
php artisan view:cache || {
    echo "⚠️ No se pudieron cachear vistas, continuando..."
}

# Optimizar autoloader
php artisan optimize:clear
composer dump-autoload --optimize

echo "🔗 Verificando storage link..."
if [ ! -L public/storage ]; then
    php artisan storage:link
fi

echo "🔐 Ajustando permisos..."
chown -R www-data:www-data .
chmod -R 755 .
chmod -R 775 storage bootstrap/cache

echo "♻️ Reiniciando servicios..."
systemctl restart php8.3-fpm
systemctl reload nginx

# Reiniciar workers si existen
if command -v supervisorctl &> /dev/null; then
    supervisorctl restart laravel-worker:* 2>/dev/null || true
fi

echo "✅ Deploy completado (sin caché de rutas)"
DEPLOY

# Verificación
echo -e "\n${YELLOW}🔍 Verificando sitio...${NC}"
sleep 3

# Probar varias rutas para confirmar que funcionan
echo "Probando rutas..."
HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://68.183.2.184)
HTTP_STATUS_ES=$(curl -s -o /dev/null -w "%{http_code}" http://68.183.2.184/es)
HTTP_STATUS_EN=$(curl -s -o /dev/null -w "%{http_code}" http://68.183.2.184/en)

if [ "$HTTP_STATUS" = "200" ] || [ "$HTTP_STATUS" = "302" ]; then
    echo -e "${GREEN}✅ Ruta principal OK (HTTP $HTTP_STATUS)${NC}"
fi

if [ "$HTTP_STATUS_ES" = "200" ] || [ "$HTTP_STATUS_ES" = "302" ]; then
    echo -e "${GREEN}✅ Ruta /es OK (HTTP $HTTP_STATUS_ES)${NC}"
fi

if [ "$HTTP_STATUS_EN" = "200" ] || [ "$HTTP_STATUS_EN" = "302" ]; then
    echo -e "${GREEN}✅ Ruta /en OK (HTTP $HTTP_STATUS_EN)${NC}"
fi

echo -e "${GREEN}✨ ¡Deploy completado exitosamente!${NC}"
echo -e "${GREEN}🔗 http://68.183.2.184${NC}"
echo -e "${YELLOW}📝 Nota: Las rutas NO están cacheadas (por Laravel Localization)${NC}"