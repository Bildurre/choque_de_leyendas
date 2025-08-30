# Guía del Comando preview:manage

## Descripción General

El comando `preview:manage` es una herramienta completa para gestionar las imágenes de preview de héroes y cartas. Permite generar, regenerar, limpiar, eliminar y verificar el estado de todas las preview images del sistema, con soporte para operaciones por facción.

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

### 5. `generate-faction` - Generar Previews de una Facción

Genera las preview images de todos los elementos de una facción específica.

```bash
# Generar previews de toda la facción (héroes y cartas)
php artisan preview:manage generate-faction --faction=1 --sync

# Solo héroes de la facción
php artisan preview:manage generate-faction --faction=1 --type=heroes --sync

# Solo cartas de la facción
php artisan preview:manage generate-faction --faction=1 --type=cards --sync

# Forzar regeneración aunque existan
php artisan preview:manage generate-faction --faction=1 --force --sync
```

**Parámetros requeridos:**
- `--faction`: ID de la facción

**Parámetros opcionales:**
- `--type`: Tipo de elementos (`all`, `heroes`, `cards`). Por defecto: `all`
- `--force`: Regenerar aunque ya existan las previews
- `--sync`: Ejecutar síncronamente
- `--dry-run`: Ver qué se haría sin ejecutar

**Casos de uso:**
- Generar previews después de añadir una nueva facción
- Regenerar tras cambios en el diseño de facción
- Actualización selectiva por facción

### 6. `regenerate-faction` - Regenerar Previews de una Facción

Fuerza la regeneración de todas las preview images de una facción.

```bash
# Regenerar toda la facción
php artisan preview:manage regenerate-faction --faction=1 --sync

# Solo héroes
php artisan preview:manage regenerate-faction --faction=1 --type=heroes --sync

# Solo cartas
php artisan preview:manage regenerate-faction --faction=1 --type=cards --sync
```

**Nota:** Esta acción es equivalente a `generate-faction` con `--force`

### 7. `delete` - Eliminar Preview Específica

Elimina las preview images de un héroe o carta específica.

```bash
# Eliminar preview de un héroe
php artisan preview:manage delete --model=hero --id=1

# Eliminar preview de una carta
php artisan preview:manage delete --model=card --id=42

# Ver qué se eliminaría sin hacerlo
php artisan preview:manage delete --model=hero --id=1 --dry-run
```

**Parámetros requeridos:**
- `--model`: Tipo de modelo (`hero` o `card`)
- `--id`: ID del modelo

**Casos de uso:**
- Limpiar previews de elementos eliminados
- Forzar regeneración completa de un elemento
- Liberar espacio de elementos específicos

### 8. `delete-all` - Eliminar TODAS las Previews

Elimina todas las preview images del sistema.

```bash
# Eliminar todas las previews (pedirá confirmación)
php artisan preview:manage delete-all

# Ver qué se eliminaría
php artisan preview:manage delete-all --dry-run
```

**⚠️ PRECAUCIÓN:** Esta acción es destructiva y eliminará TODAS las imágenes de preview.

**Casos de uso:**
- Limpieza completa antes de cambio mayor en el sistema
- Reset total del sistema de previews
- Liberación masiva de espacio en disco

### 9. `delete-heroes` - Eliminar Todas las Previews de Héroes

Elimina todas las preview images de héroes.

```bash
# Eliminar todas las previews de héroes
php artisan preview:manage delete-heroes

# Ver qué se eliminaría
php artisan preview:manage delete-heroes --dry-run
```

**Casos de uso:**
- Regeneración completa de héroes
- Cambios en el diseño específico de héroes
- Limpieza selectiva por tipo

### 10. `delete-cards` - Eliminar Todas las Previews de Cartas

Elimina todas las preview images de cartas.

```bash
# Eliminar todas las previews de cartas
php artisan preview:manage delete-cards

# Ver qué se eliminaría
php artisan preview:manage delete-cards --dry-run
```

**Casos de uso:**
- Regeneración completa de cartas
- Cambios en el diseño específico de cartas
- Limpieza selectiva por tipo

### 11. `delete-faction` - Eliminar Previews de una Facción

Elimina las preview images de todos los elementos de una facción.

```bash
# Eliminar todas las previews de la facción
php artisan preview:manage delete-faction --faction=1

# Solo héroes de la facción
php artisan preview:manage delete-faction --faction=1 --type=heroes

# Solo cartas de la facción
php artisan preview:manage delete-faction --faction=1 --type=cards

# Ver qué se eliminaría
php artisan preview:manage delete-faction --faction=1 --dry-run
```

**Parámetros requeridos:**
- `--faction`: ID de la facción

**Parámetros opcionales:**
- `--type`: Tipo de elementos (`all`, `heroes`, `cards`). Por defecto: `all`

**Casos de uso:**
- Limpieza tras eliminar una facción
- Regeneración completa de una facción
- Mantenimiento selectivo por facción

### 12. `clean` - Limpiar Imágenes Huérfanas

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
- `generate-faction`: Regenera las de la facción

### `--dry-run`
Simula la operación sin ejecutar cambios reales.

```bash
php artisan preview:manage clean --dry-run
```

**Útil para:**
- Ver qué cambios se harían
- Verificar antes de operaciones destructivas
- Testing de comandos

### `--faction`
Especifica el ID de la facción para operaciones por facción.

```bash
php artisan preview:manage generate-faction --faction=1
```

**Usado en:**
- `generate-faction`
- `regenerate-faction`
- `delete-faction`

### `--type`
Filtra el tipo de elementos en operaciones de facción.

```bash
php artisan preview:manage generate-faction --faction=1 --type=heroes
```

**Valores válidos:**
- `all`: Héroes y cartas (por defecto)
- `heroes`: Solo héroes
- `cards`: Solo cartas

**Usado en:**
- `generate-faction`
- `regenerate-faction`
- `delete-faction`

### `--model`
Especifica el tipo de modelo para operaciones específicas.

```bash
php artisan preview:manage generate --model=hero --id=1
```

**Valores válidos:**
- `hero`: Héroe
- `card`: Carta

**Usado en:**
- `generate`
- `delete`

### `--id`
Especifica el ID del modelo para operaciones específicas.

```bash
php artisan preview:manage generate --model=card --id=42
```

**Usado en:**
- `generate`
- `delete`

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

### Workflow 2: Mantenimiento por Facción

```bash
# 1. Ver estado de la facción (manual por ahora)
php artisan preview:manage status

# 2. Regenerar toda la facción
php artisan preview:manage regenerate-faction --faction=1 --sync

# 3. Verificar resultado
php artisan preview:manage status
```

### Workflow 3: Actualización de Facción Específica

```bash
# 1. Eliminar previews antiguas de la facción
php artisan preview:manage delete-faction --faction=2

# 2. Generar nuevas previews
php artisan preview:manage generate-faction --faction=2 --sync

# 3. Limpiar huérfanos si es necesario
php artisan preview:manage clean
```

### Workflow 4: Reset Completo de Cartas

```bash
# 1. Eliminar todas las previews de cartas
php artisan preview:manage delete-cards

# 2. Regenerar todas las cartas
php artisan preview:manage generate-all --type=cards --sync

# 3. Verificar estado
php artisan preview:manage status
```

### Workflow 5: Limpieza y Optimización

```bash
# 1. Ver estado actual y espacio usado
php artisan preview:manage status

# 2. Detectar huérfanos
php artisan preview:manage clean --dry-run

# 3. Limpiar si hay huérfanos
php artisan preview:manage clean

# 4. Generar faltantes
php artisan preview:manage generate-all
```

### Workflow 6: Debugging de Preview Específica

```bash
# 1. Eliminar preview problemática
php artisan preview:manage delete --model=hero --id=25

# 2. Regenerar con sincronía para ver errores
php artisan preview:manage generate --model=hero --id=25 --sync

# 3. Si persiste el problema, verificar en el sistema
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

### Problema: Facción no genera previews

**Solución:**
```bash
# Verificar que la facción existe
php artisan tinker
> \App\Models\Faction::find(ID)

# Eliminar y regenerar
php artisan preview:manage delete-faction --faction=ID
php artisan preview:manage generate-faction --faction=ID --sync
```

## Mejores Prácticas

1. **Usa Queue para Operaciones Masivas**
   - Más de 50 elementos → usar queue
   - Menos de 50 → puede usar --sync

2. **Dry Run Antes de Eliminar**
   ```bash
   # Siempre verificar antes de eliminar
   php artisan preview:manage delete-all --dry-run
   php artisan preview:manage delete-faction --faction=1 --dry-run
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

5. **Mantenimiento por Facción**
   - Trabajar con facciones individualmente para mejor control
   - Útil cuando se actualiza el diseño de una facción específica

6. **Logs y Debugging**
   ```bash
   # Ver logs de generación
   tail -f storage/logs/laravel.log | grep -i preview
   ```

## Ejemplos Avanzados

### Regenerar Solo una Facción con Verificación

```bash
#!/bin/bash
FACTION_ID=3

echo "Regenerando facción $FACTION_ID"

# Verificar estado antes
echo "Estado inicial:"
php artisan preview:manage status

# Eliminar previews actuales
php artisan preview:manage delete-faction --faction=$FACTION_ID

# Generar nuevas
php artisan preview:manage generate-faction --faction=$FACTION_ID --sync

# Verificar resultado
echo "Estado final:"
php artisan preview:manage status
```

### Script de Mantenimiento Completo

```bash
#!/bin/bash
echo "=== Mantenimiento de Previews ==="

# 1. Estado inicial
php artisan preview:manage status > /tmp/preview_status_before.txt

# 2. Limpiar huérfanos
php artisan preview:manage clean

# 3. Generar faltantes
php artisan preview:manage generate-all

# 4. Procesar queue
timeout 300 php artisan queue:work --stop-when-empty

# 5. Estado final
php artisan preview:manage status > /tmp/preview_status_after.txt

# 6. Mostrar diferencias
diff /tmp/preview_status_before.txt /tmp/preview_status_after.txt
```

## Consideraciones de Rendimiento

- **Generación Síncrona**: ~2-4 segundos por imagen
- **Con Queue**: Puede procesar en paralelo
- **Memoria**: ~50-100MB por proceso de generación
- **Disco**: ~200-500KB por preview image
- **Por Facción**: Reduce la carga procesando grupos más pequeños

## Seguridad

- El comando requiere acceso CLI (no disponible vía web)
- Verificar permisos de escritura en `storage/app/public/images/previews/`
- Las operaciones de eliminación siempre piden confirmación (excepto con --dry-run)
- Las imágenes huérfanas se confirman antes de eliminar