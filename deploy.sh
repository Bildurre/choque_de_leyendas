#!/bin/bash
# Deploy script para Choque de Leyendas

echo "🚀 Iniciando deployment..."

# Push a Git
git add .
git commit -m "Update: $(date +%Y-%m-%d_%H:%M:%S)"
git push origin production

echo "📥 Conectando al servidor..."
ssh universoalanda@hl1293.dinaserver.com << 'EOF'
cd ~/www/choquedeleyendas/laravel_app
git pull origin production
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:clear
npm install
npm run build
cp -r public/build/* ../public_html/build/
echo "✅ Deployment completado"
EOF