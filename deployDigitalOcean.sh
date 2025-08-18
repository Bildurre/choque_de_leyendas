#!/bin/bash
# Deploy script para Choque de Leyendas - DigitalOcean

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}🚀 Iniciando deployment...${NC}"

# Verificar que estamos en main
CURRENT_BRANCH=$(git branch --show-current)
if [ "$CURRENT_BRANCH" != "main" ]; then
    echo -e "${RED}❌ Error: No estás en la rama main. Actual: $CURRENT_BRANCH${NC}"
    exit 1
fi

# 1. PUSH DE CAMBIOS LOCALES
echo -e "${YELLOW}📝 Subiendo cambios...${NC}"
git add .
git commit -m "Deploy: $(date +%Y-%m-%d_%H:%M:%S)" || echo "Sin cambios nuevos"
git push origin main

# Merge main → production
git checkout production
git merge main -m "Deploy: $(date +%Y-%m-%d_%H:%M:%S)"
git push origin production
git checkout main

# 2. DEPLOY EN EL SERVIDOR
echo -e "${YELLOW}🌐 Conectando al servidor...${NC}"
ssh root@68.183.2.184 << 'DEPLOY'
set -e  # Salir si hay errores

cd /var/www/laravel-game-cards

echo "📥 Actualizando código..."
# Forzar actualización (descarta cambios locales del servidor)
git fetch origin production
git reset --hard origin/production

echo "📦 Instalando dependencias..."
composer install --no-dev --optimize-autoloader --no-interaction
npm ci --production=false

echo "🎨 Compilando assets..."
npm run build

echo "🗄️ Migraciones..."
php artisan migrate --force

echo "⚡ Optimizando..."
# Limpiar todo primero
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Cachear todo
php artisan config:cache
php artisan route:cache
php artisan view:cache || true  # No fallar si hay problemas con vistas

# Storage link
php artisan storage:link || true

echo "🔐 Permisos..."
chown -R www-data:www-data .
chmod -R 755 .
chmod -R 775 storage bootstrap/cache

echo "♻️ Reiniciando servicios..."
systemctl reload nginx
systemctl restart php8.3-fpm
supervisorctl restart laravel-worker:* || true

echo "✅ Deploy completado"
DEPLOY

# Verificar que el sitio responde
echo -e "\n${YELLOW}🔍 Verificando sitio...${NC}"
HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://68.183.2.184)

if [ "$HTTP_STATUS" = "200" ] || [ "$HTTP_STATUS" = "302" ]; then
    echo -e "${GREEN}✨ ¡Deploy exitoso!${NC}"
    echo -e "${GREEN}🔗 Sitio disponible en: http://68.183.2.184${NC}"
else
    echo -e "${RED}⚠️ Advertencia: El sitio devolvió código HTTP $HTTP_STATUS${NC}"
fi