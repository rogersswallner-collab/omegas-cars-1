# Módulo de Proveedores - Omega Cars

## Descripción

El módulo de proveedores permite gestionar la información de los proveedores y fabricantes, así como relacionarlos con los productos del inventario. Permite diferenciar entre productos comprados a proveedores externos y productos fabricados internamente.

## Características Principales

### 1. Gestión de Proveedores
- **CRUD completo**: Crear, leer, actualizar y eliminar proveedores
- **Información del proveedor**:
  - Nombre del contacto (requerido)
  - Empresa
  - Email
  - Teléfono
  - Dirección
  - País
  - Notas adicionales
- **Estadísticas**: Ver cantidad de productos y stock total por proveedor

### 2. Integración con Productos
- **Campo de origen**: Cada producto puede ser marcado como "Comprado" o "Fabricado"
- **Relación con proveedor**: Los productos comprados pueden asociarse a un proveedor específico
- **Visualización**: En el catálogo de productos se muestra el origen y proveedor (si aplica)

## Instalación

### Para Base de Datos Nueva
Si estás instalando el sistema por primera vez, simplemente ejecuta:
```sql
source db/database.sql
```

### Para Base de Datos Existente
Si ya tienes el sistema funcionando, ejecuta el script de actualización:
```sql
source db/update_add_providers.sql
```

Este script:
- Crea la tabla `proveedores`
- Agrega las columnas `origen` y `proveedor_id` a la tabla `productos`
- Establece la relación de clave foránea

## Estructura de la Base de Datos

### Tabla: proveedores
```sql
CREATE TABLE proveedores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  empresa VARCHAR(100),
  email VARCHAR(100),
  telefono VARCHAR(20),
  direccion TEXT,
  pais VARCHAR(50),
  notas TEXT,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Tabla: productos (cambios)
```sql
ALTER TABLE productos
ADD COLUMN origen ENUM('comprado', 'fabricado') DEFAULT 'comprado',
ADD COLUMN proveedor_id INT DEFAULT NULL,
ADD FOREIGN KEY (proveedor_id) REFERENCES proveedores(id) ON DELETE SET NULL;
```

## Uso del Módulo

### Acceder al Módulo de Proveedores
1. Inicia sesión en el sistema
2. Desde el dashboard, haz clic en **"Gestión de Proveedores"**

### Agregar un Proveedor
1. En la página de proveedores, completa el formulario:
   - **Nombre del Contacto**: Campo obligatorio
   - Completa los demás campos opcionales según sea necesario
2. Haz clic en **"Guardar Proveedor"**

### Editar un Proveedor
1. En la lista de proveedores, haz clic en **"Editar"**
2. Modifica los campos necesarios
3. Haz clic en **"Actualizar Proveedor"**

### Eliminar un Proveedor
1. Haz clic en **"Eliminar"** en la tarjeta del proveedor
2. Confirma la acción
3. **Nota**: Los productos asociados no se eliminarán, solo se quitará la relación

### Asociar Productos con Proveedores
1. Ve a **"Gestión de Productos"**
2. Al crear o editar un producto:
   - Selecciona el **origen**: "Comprado" o "Fabricado"
   - Si seleccionas "Comprado", elige un proveedor del menú desplegable
   - Si seleccionas "Fabricado", el proveedor no es necesario
3. Guarda el producto

## Archivos del Módulo

### Nuevos Archivos
- `providers.php` - Página principal de gestión de proveedores
- `assets/css/providers.css` - Estilos para la página de proveedores
- `db/update_add_providers.sql` - Script de actualización SQL

### Archivos Modificados
- `dashboard.php` - Agregado enlace al módulo de proveedores
- `products.php` - Agregados campos de origen y proveedor
- `assets/css/products.css` - Estilos para los nuevos campos
- `db/database.sql` - Schema actualizado con tabla de proveedores

## Flujo de Trabajo Recomendado

1. **Primero**: Registra tus proveedores en el módulo de proveedores
2. **Segundo**: Al crear productos, selecciona si son comprados o fabricados
3. **Tercero**: Asigna el proveedor correspondiente a los productos comprados
4. **Monitoreo**: Revisa las estadísticas de cada proveedor (productos y stock)

## Ventajas del Módulo

- **Trazabilidad**: Saber de dónde proviene cada producto
- **Gestión de relaciones**: Mantener información de contacto de proveedores
- **Análisis**: Ver qué proveedores suministran más productos
- **Flexibilidad**: Diferenciar entre productos comprados y fabricados
- **Escalabilidad**: Fácil de extender para agregar más funcionalidades

## Próximas Mejoras Sugeridas

- Historial de compras a proveedores
- Precios por proveedor
- Evaluación de proveedores
- Reportes de compras por proveedor
- Órdenes de compra
- Gestión de cotizaciones

## Soporte

Para más información o soporte, contacta al equipo de desarrollo.
