#!/bin/bash
# Deploy script - Sin cache de rutas para localización

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

echo "🧹 Limpiando cachés..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "⚡ Optimizando (SIN cache de rutas)..."
# Cache de configuración - SÍ
php artisan config:cache

# Cache de rutas - NO (problema con localización)
# php artisan route:cache  # COMENTADO - No cachear rutas

# Cache de vistas - SÍ
php artisan view:cache

# Optimización general sin rutas
php artisan optimize:clear
php artisan optimize --no-cache-routes

echo "🔗 Verificando storage link..."
php artisan storage:link 2>/dev/null || true

echo "🔐 Ajustando permisos..."
chown -R www-data:www-data .
chmod -R 755 .
chmod -R 775 storage bootstrap/cache

echo "♻️ Reiniciando servicios..."
systemctl restart php8.3-fpm
systemctl reload nginx
supervisorctl restart laravel-worker:* 2>/dev/null || true

echo "✅ Deploy completado"
DEPLOY

echo -e "\n${YELLOW}🔍 Verificando...${NC}"
sleep 2
HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://68.183.2.184)

if [ "$HTTP_STATUS" = "200" ] || [ "$HTTP_STATUS" = "302" ]; then
    echo -e "${GREEN}✨ ¡Deploy exitoso!${NC}"
    echo -e "${GREEN}🔗 http://68.183.2.184${NC}"
else
    echo -e "${YELLOW}⚠️ HTTP $HTTP_STATUS - Verificar manualmente${NC}"
fi