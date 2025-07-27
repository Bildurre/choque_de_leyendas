# Guía de Deployment - Choque de Leyendas

## Información del Servidor

- **Host**: hl1293.dinaserver.com
- **Usuario SSH**: universoalanda
- **Directorio Laravel**: `~/www/choquedeleyendas/laravel_app`
- **Directorio Público**: `~/www/choquedeleyendas/public_html`
- **URL**: https://choquedeleyendas.universoalanda.com

## Flujo de Ramas

- **`main`**: Rama de desarrollo (trabajo local)
- **`production`**: Rama de producción (servidor)

## Proceso de Deployment

### 1. Desarrollo Local (rama main)

```bash
# Asegúrate de estar en main
git checkout main

# Haz tus cambios y commits
git add .
git commit -m "Descripción del cambio"
git push origin main
```

### 2. Cuando estés listo para subir a producción

#### Opción A: Usar el script automático
```bash
./deploy.sh
```

#### Opción B: Proceso manual

**En tu máquina local:**
```bash
# Asegúrate de que main está actualizado
git checkout main
git pull origin main

# Cambiar a production y hacer merge
git checkout production
git merge main -m "Deploy: $(date)"
git push origin production

# Volver a main para seguir trabajando
git checkout main
```

**Conectar al servidor:**
```bash
ssh universoalanda@hl1293.dinaserver.com
cd ~/www/choquedeleyendas/laravel_app
```

**En el servidor, actualizar según lo que hayas cambiado:**

### 3. Comandos según tipo de cambio

#### Siempre ejecutar primero:
```bash
git pull origin production
```

#### Si cambiaste archivos PHP o composer.json:
```bash
composer install --no-dev --optimize-autoloader
```

#### Si cambiaste archivos JS/CSS/SCSS:
```bash
npm run build
cp -r public/build/* ../public_html/build/
```

#### Si añadiste nuevas dependencias de Node:
```bash
npm install
npm run build
cp -r public/build/* ../public_html/build/
```

#### Si cambiaste migraciones o estructura de BD:
```bash
php artisan migrate --force
```

#### Si cambiaste archivos de configuración:
```bash
php artisan config:cache
```

#### Si cambiaste rutas:
```bash
php artisan route:clear
```

#### Si cambiaste traducciones:
```bash
php artisan cache:clear
```

### 4. Siempre al final:
```bash
php artisan config:cache
php artisan route:clear
```

## Subir archivos de Storage

Para imágenes, PDFs y otros archivos:

1. Usar FileZilla o similar
2. Conectar con los mismos datos SSH
3. Navegar a: `/home/universoalanda/www/choquedeleyendas/laravel_app/storage/app/public/`
4. Subir archivos manteniendo la estructura de carpetas

## Comandos útiles

### Ver logs de error:
```bash
tail -f storage/logs/laravel.log
```

### Limpiar todos los cachés:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Regenerar cachés de producción:
```bash
php artisan config:cache
php artisan route:cache
```

## Notas importantes

- **No usar** `php artisan view:cache` (da error con los componentes)
- **Siempre** hacer backup de la BD antes de migraciones importantes
- **Verificar** permisos después de subir archivos nuevos:
  ```bash
  chmod -R 755 storage bootstrap/cache
  ```

## Troubleshooting

### Error 500:
1. Activar debug temporalmente en `.env`
2. Ver logs: `tail -30 storage/logs/laravel.log`
3. Verificar permisos de carpetas

### Cambios no se reflejan:
1. Limpiar todos los cachés
2. Verificar que el build de assets se copió correctamente
3. Limpiar caché del navegador

### Base de datos:
- Usuario: `unive_cdl`
- Base de datos: `unive_cdl`
- Acceso por phpMyAdmin desde el panel de Dinahosting