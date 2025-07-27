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
ssh universoalanda@hl1293.dinaserver.com << 'EOF'
cd ~/www/choquedeleyendas/laravel_app

echo "Actualizando código..."
git pull origin production

echo "Instalando dependencias PHP..."
composer install --no-dev --optimize-autoloader

echo "Ejecutando migraciones..."
php artisan migrate --force

echo "Limpiando y optimizando..."
php artisan config:cache
php artisan route:clear

echo "Compilando assets..."
npm install
npm run build

echo "Copiando assets a public_html..."
cp -r public/build/* ../public_html/build/

echo "Deployment completado"
EOF

echo "¡Deploy finalizado con éxito!"