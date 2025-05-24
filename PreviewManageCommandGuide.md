# Guía del Comando preview:manage

## Descripción General

El comando `preview:manage` es una herramienta completa para gestionar las imágenes de preview de héroes y cartas. Permite generar, regenerar, limpiar y verificar el estado de todas las preview images del sistema.

## Sintaxis Básica

```bash
php artisan preview:manage {action} [options]
```

## Acciones Disponibles

### 1. `status` - Ver Estado Actual

Muestra estadísticas detalladas sobre las preview images existentes.

```bash
php artisan preview:manage status
```

**Información mostrada:**
- Total de héroes/cartas con previews completas
- Héroes/cartas con previews parciales (falta algún idioma)
- Héroes/cartas sin ninguna preview
- Espacio en disco utilizado

**Ejemplo de salida:**
```
=== HEROES STATUS ===
+----------+-------+------------+
| Status   | Count | Percentage |
+----------+-------+------------+
| Complete | 45    | 90%        |
| Partial  | 3     | 6%         |
| Missing  | 2     | 4%         |
| Total    | 50    | 100%       |
+----------+-------+------------+

=== DISK USAGE ===
+--------+----------+
| Type   | Size     |
+--------+----------+
| Heroes | 125.4 MB |
| Cards  | 89.2 MB  |
| Total  | 214.6 MB |
+--------+----------+
```

### 2. `generate-all` - Generar Previews Faltantes

Genera solo las preview images que faltan (no regenera las existentes).

```bash
# Usando queue (recomendado para muchas imágenes)
php artisan preview:manage generate-all

# Ejecutar síncronamente
php artisan preview:manage generate-all --sync

# Ver qué se generaría sin ejecutar
php artisan preview:manage generate-all --dry-run
```

**Casos de uso:**
- Primera generación masiva después de importar datos
- Completar previews después de añadir un nuevo idioma
- Generar previews de nuevos héroes/cartas

### 3. `regenerate` - Regenerar Todas las Previews

Fuerza la regeneración de TODAS las preview images, incluso las existentes.

```bash
# Regenerar todo síncronamente
php artisan preview:manage regenerate --sync

# Regenerar todo usando queue
php artisan preview:manage regenerate
```

**Casos de uso:**
- Después de cambiar el diseño de las previews
- Después de actualizar CSS o estilos
- Corrección de problemas en las imágenes existentes

### 4. `generate` - Generar Preview Específica

Genera las preview images de un héroe o carta específica.

```bash
# Generar preview de un héroe
php artisan preview:manage generate --model=hero --id=1 --sync

# Generar preview de una carta
php artisan preview:manage generate --model=card --id=42 --sync

# Forzar regeneración aunque ya exista
php artisan preview:manage generate --model=hero --id=1 --force --sync
```

**Parámetros requeridos:**
- `--model`: Tipo de modelo (`hero` o `card`)
- `--id`: ID del modelo en la base de datos

**Casos de uso:**
- Regenerar preview de un elemento específico tras editarlo
- Testing y depuración
- Regeneración selectiva

### 5. `clean` - Limpiar Imágenes Huérfanas

Detecta y elimina archivos de preview que no están asociados a ningún modelo.

```bash
# Ver archivos huérfanos sin eliminar
php artisan preview:manage clean --dry-run

# Limpiar archivos huérfanos (pedirá confirmación)
php artisan preview:manage clean
```

**Qué detecta como huérfano:**
- Imágenes de modelos eliminados
- Imágenes con nombres antiguos tras cambiar slugs
- Archivos corruptos o mal nombrados

## Opciones Globales

### `--sync`
Ejecuta las operaciones síncronamente en lugar de usar la queue.

```bash
php artisan preview:manage generate-all --sync
```

**Ventajas:**
- Ejecución inmediata
- Ver errores en tiempo real
- No requiere queue worker

**Desventajas:**
- Bloquea la terminal
- Timeout en operaciones largas
- No aprovecha procesamiento paralelo

### `--force`
Fuerza la operación incluso si ya existen los elementos.

```bash
php artisan preview:manage generate-all --force
```

**Aplica a:**
- `generate-all`: Regenera incluso las existentes
- `generate`: Regenera la preview específica

### `--dry-run`
Simula la operación sin ejecutar cambios reales.

```bash
php artisan preview:manage clean --dry-run
```

**Útil para:**
- Ver qué cambios se harían
- Verificar antes de operaciones destructivas
- Testing de comandos

## Workflows Comunes

### Workflow 1: Primera Configuración

```bash
# 1. Ver estado inicial
php artisan preview:manage status

# 2. Generar todas las previews faltantes
php artisan preview:manage generate-all

# 3. Iniciar workers para procesar
php artisan queue:work

# 4. Verificar resultado
php artisan preview:manage status
```

### Workflow 2: Después de Cambios de Diseño

```bash
# 1. Regenerar todas las previews
php artisan preview:manage regenerate --sync

# 2. Limpiar archivos antiguos si cambiaron nombres
php artisan preview:manage clean
```

### Workflow 3: Mantenimiento Regular

```bash
# 1. Verificar estado
php artisan preview:manage status

# 2. Generar faltantes
php artisan preview:manage generate-all

# 3. Limpiar huérfanos
php artisan preview:manage clean --dry-run
php artisan preview:manage clean  # Si hay huérfanos
```

### Workflow 4: Debugging de Preview Específica

```bash
# 1. Generar preview de un héroe problemático
php artisan preview:manage generate --model=hero --id=25 --sync

# 2. Si no funciona, forzar regeneración
php artisan preview:manage generate --model=hero --id=25 --force --sync

# 3. Verificar en el sistema de archivos
ls -la storage/app/public/images/previews/heroes/es/
```

## Integración con Queue

### Configuración de Workers

Para procesar las previews en background:

```bash
# Worker simple
php artisan queue:work

# Worker con opciones optimizadas para previews
php artisan queue:work --queue=default --sleep=3 --tries=3 --timeout=120

# Múltiples workers para procesamiento paralelo
php artisan queue:work &
php artisan queue:work &
php artisan queue:work &
```

### Monitoreo de Queue

```bash
# Ver jobs pendientes
php artisan queue:monitor

# Reintentar jobs fallidos
php artisan queue:retry all

# Limpiar jobs fallidos
php artisan queue:flush
```

## Resolución de Problemas

### Problema: "No CSS file found for preview generation"

**Solución:**
```bash
# Compilar assets
npm run build

# Luego regenerar
php artisan preview:manage regenerate --sync
```

### Problema: Timeout al generar muchas imágenes

**Solución:**
```bash
# Usar queue en lugar de --sync
php artisan preview:manage generate-all

# En otra terminal
php artisan queue:work --timeout=300
```

### Problema: Imágenes no se actualizan tras cambios

**Solución:**
```bash
# Forzar regeneración
php artisan preview:manage regenerate --force --sync

# Limpiar caché del navegador
# O añadir versioning a las URLs
```

## Mejores Prácticas

1. **Usa Queue para Operaciones Masivas**
   - Más de 50 elementos → usar queue
   - Menos de 50 → puede usar --sync

2. **Dry Run Antes de Limpiar**
   ```bash
   php artisan preview:manage clean --dry-run
   ```

3. **Monitorea el Espacio en Disco**
   ```bash
   php artisan preview:manage status
   ```

4. **Automatización con Cron**
   ```cron
   # Generar previews faltantes cada noche
   0 2 * * * cd /path/to/project && php artisan preview:manage generate-all
   
   # Limpiar huérfanos semanalmente
   0 3 * * 0 cd /path/to/project && php artisan preview:manage clean
   ```

5. **Logs y Debugging**
   ```bash
   # Ver logs de generación
   tail -f storage/logs/laravel.log | grep -i preview
   ```

## Ejemplos Avanzados

### Generar solo para un idioma específico

Aunque el comando no tiene opción de idioma, puedes modificar temporalmente la configuración:

```php
// En tinker
config(['laravellocalization.supportedLocales' => ['es' => []]]);
// Luego ejecutar el comando
```

### Script de Regeneración Completa

```bash
#!/bin/bash
echo "Iniciando regeneración completa de previews..."

# Limpiar queue
php artisan queue:flush

# Regenerar todas
php artisan preview:manage regenerate

# Iniciar workers
for i in {1..4}; do
    php artisan queue:work --daemon --tries=3 --timeout=120 &
done

# Esperar a que terminen
while [ $(php artisan queue:monitor | grep -c "pending") -gt 0 ]; do
    sleep 10
done

# Limpiar huérfanos
php artisan preview:manage clean

# Mostrar estado final
php artisan preview:manage status
```

## Consideraciones de Rendimiento

- **Generación Síncrona**: ~2-4 segundos por imagen
- **Con Queue**: Puede procesar en paralelo
- **Memoria**: ~50-100MB por proceso de generación
- **Disco**: ~200-500KB por preview image

## Seguridad

- El comando requiere acceso CLI (no disponible vía web)
- Verificar permisos de escritura en `storage/app/public/images/previews/`
- Las imágenes huérfanas se confirman antes de eliminar