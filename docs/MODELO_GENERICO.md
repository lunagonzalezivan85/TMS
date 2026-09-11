# Modelo Genérico - Sistema GMV

## Descripción
El sistema incluye un modelo genérico (`BaseModel` y `GenericModel`) que permite trabajar con cualquier tabla de la base de datos y ejecutar procedimientos almacenados de forma sencilla.

## Características Principales

### 1. BaseModel
- **Auditoría automática**: Establece automáticamente `usuarioCrea` y `usuarioEdita`
- **Códigos consecutivos**: Genera códigos automáticos sin huecos
- **Transacciones**: Manejo completo de transacciones
- **Paginación**: Sistema de paginación integrado
- **Búsquedas**: Búsqueda por texto en múltiples campos
- **Procedimientos almacenados**: Ejecución flexible de SPs

### 2. GenericModel
- **Tabla dinámica**: Permite trabajar con cualquier tabla
- **SPs predefinidos**: Métodos para procedimientos comunes del sistema
- **Fácil instanciación**: Creación rápida de instancias para tablas específicas

## Uso Básico

### Trabajar con una tabla específica
```php
// Crear instancia para tabla 'vehiculos'
$vehiculoModel = GenericModel::tabla('vehiculos');

// Obtener todos los registros
$vehiculos = $vehiculoModel->findAll();

// Insertar nuevo registro
$nuevoId = $vehiculoModel->insert([
    'placa' => 'ABC-123',
    'marca' => 'Toyota',
    'modelo' => 'Corolla'
]);

// Actualizar registro
$vehiculoModel->update($id, ['kilometraje' => 50000]);

// Eliminar registro
$vehiculoModel->delete($id);
```

### Insertar con código consecutivo
```php
$vehiculoModel = GenericModel::tabla('vehiculos');

$nuevoId = $vehiculoModel->insertarConCodigo([
    'id_empresa' => 1,
    'placa' => 'XYZ-999',
    'marca' => 'Honda'
], 'vehiculos'); // Tipo de tabla para el consecutivo
```

## Procedimientos Almacenados

### Ejecutar SP que retorna datos (SELECT)
```php
$genericModel = new GenericModel();

// SP con parámetros
$vehiculos = $genericModel->ejecutarSP(
    'sp_vehiculos_con_conductor', // Nombre del SP
    [1], // Parámetros: [empresaId]
    'select', // Tipo de operación
    true // Retornar datos
);

// SP sin parámetros
$estadisticas = $genericModel->ejecutarSP('sp_estadisticas_generales');
```

### Ejecutar SP de modificación (INSERT/UPDATE/DELETE)
```php
// SP de inserción
$resultado = $genericModel->ejecutarSP(
    'sp_insertar_solicitud',
    [1, 1, 1, 'Descripción'], // Parámetros
    'insert', // Tipo de operación
    false // No retornar datos
);

// SP de actualización
$actualizado = $genericModel->ejecutarSP(
    'sp_completar_mantenimiento',
    [1, 150.50, 'Completado'], // Parámetros
    'update',
    false
);
```

## Funciones Avanzadas

### Búsqueda por texto
```php
$vehiculoModel = GenericModel::tabla('vehiculos');

$resultados = $vehiculoModel->buscar(
    'Toyota', // Texto a buscar
    ['marca', 'modelo', 'placa'], // Campos donde buscar
    ['id_empresa' => 1] // Filtros adicionales
);
```

### Paginación
```php
$vehiculos = $vehiculoModel->getPaginado(
    1, // Página
    10, // Registros por página
    ['estado' => 'ACTIVO'], // Filtros
    [ // Joins opcionales
        [
            'table' => 'conductores',
            'condition' => 'conductores.id = vehiculos.id_conductor',
            'type' => 'left'
        ]
    ]
);

// Resultado:
// [
//     'data' => [...], // Registros
//     'total' => 50, // Total de registros
//     'page' => 1, // Página actual
//     'perPage' => 10, // Registros por página
//     'totalPages' => 5 // Total de páginas
// ]
```

### Estadísticas
```php
$estadisticas = $vehiculoModel->getEstadisticas(
    'estado', // Campo para agrupar
    ['id_empresa' => 1] // Filtros
);

// Resultado:
// [
//     ['estado' => 'ACTIVO', 'cantidad' => 25],
//     ['estado' => 'INACTIVO', 'cantidad' => 5],
//     ['estado' => 'EN REPARACION', 'cantidad' => 3]
// ]
```

### Transacciones
```php
$solicitudModel = GenericModel::tabla('solicitudes');

// Iniciar transacción
$solicitudModel->iniciarTransaccion();

try {
    // Operaciones múltiples
    $solicitudId = $solicitudModel->insert([...]);
    $materialModel = GenericModel::tabla('consumo_materiales');
    $materialModel->insert([...]);
    
    // Confirmar transacción
    $exito = $solicitudModel->confirmarTransaccion();
    
} catch (Exception $e) {
    // Cancelar en caso de error
    $solicitudModel->cancelarTransaccion();
}
```

### Consultas SQL personalizadas
```php
$resultados = $genericModel->ejecutarSQL(
    "SELECT v.placa, c.nombre 
     FROM vehiculos v 
     JOIN conductores c ON c.id = v.id_conductor 
     WHERE v.id_empresa = ?",
    [1], // Parámetros
    true // Retornar datos
);
```

## Procedimientos Almacenados Predefinidos

El `GenericModel` incluye métodos para procedimientos comunes:

```php
$genericModel = new GenericModel();

// Estadísticas del dashboard
$stats = $genericModel->getEstadisticasDashboard(1);

// Vehículos con conductor
$vehiculos = $genericModel->getVehiculosConConductor(1);

// Solicitudes pendientes
$pendientes = $genericModel->getSolicitudesPendientes(1);

// Completar mantenimiento
$completado = $genericModel->completarMantenimiento(1, 250.50, 'Observaciones');

// Cambiar estado de vehículo
$cambiado = $genericModel->cambiarEstadoVehiculo(1, 'EN REPARACION', 'Motivo');

// Reporte de mantenimientos
$reporte = $genericModel->getReporteMantenimientos(1, '2024-01-01', '2024-12-31');
```

## Utilidades Adicionales

### Verificar existencia
```php
$existe = $vehiculoModel->existe([
    'placa' => 'ABC-123',
    'id_empresa' => 1
]);
```

### Obtener siguiente consecutivo
```php
$consecutivo = $genericModel->getSiguienteConsecutivo(1, 'vehiculos');
// Resultado: ['codigo' => 'C2024200-0005', 'numero' => 5]
```

### Configurar campos permitidos
```php
$vehiculoModel->setAllowedFields([
    'placa', 'marca', 'modelo', 'anio', 'kilometraje'
]);
```

## Ejemplos de Uso en Controladores

Ver el archivo `app/Controllers/EjemplosUso.php` para ejemplos completos de implementación.

## Notas Importantes

1. **Auditoría automática**: Los campos `usuarioCrea` y `usuarioEdita` se establecen automáticamente
2. **Códigos consecutivos**: Se generan automáticamente usando el SP `generar_codigo`
3. **Manejo de errores**: Todos los métodos incluyen manejo de excepciones
4. **Logs**: Los errores se registran automáticamente en los logs de CodeIgniter
5. **Sesión**: El sistema obtiene el `user_id` de la sesión actual para auditoría
