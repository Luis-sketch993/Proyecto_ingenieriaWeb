# 🔍 Guía Rápida de Referencia

## 🏠 URLs Principales

| Página | URL | Descripción |
|--------|-----|-------------|
| **Inicio** | `/index.php` | Página de inicio |
| **Tienda** | `/productos.php` | Todos los productos |
| **Categoría** | `/celulares.php`, `/computadoras.php`, etc. | Por categoría |
| **Carrito** | `/carrito.php` | Ver carrito |
| **Checkout** | `/finalizar_compra.php` | Finalizar compra |
| **Login** | `/login.php` | Iniciar sesión |
| **Registro** | `/register.php` | Crear cuenta |
| **Admin** | `/admin/login.php` | Panel administrador |

---

## 💳 Métodos de Pago

### Tarjeta de Crédito (Simulada)
```
Formulario: /simular_pago_tarjeta.php?pedido=1
Campos:
  - Número: 16 dígitos (ej: 1234567890123456)
  - Titular: Nombre completo
  - Expiración: MM/AA (ej: 12/25)
  - CVV: 3-4 dígitos (ej: 123)
Resultado: Pago inmediato
```

### Yape/Plin (Manual)
```
Página: /pago_yape.php?pedido=1
Pasos:
  1. Escanea QR (image/yape2.jpg)
  2. Abre app Yape o Plin
  3. Confirma monto
  4. Realiza transferencia
  5. Vuelve a página
  6. Haz click en "Confirmar Pago"
Resultado: Pago manual confirmado
```

---

## 🔐 Variables de Sesión

```php
// Session de usuario logueado:
$_SESSION['usuario_id']     // ID único (INT)
$_SESSION['usuario']        // Nombre de usuario (STRING)
$_SESSION['email']          // Email (STRING)
$_SESSION['rol']            // Rol: 'admin', 'usuario' (STRING)

// Carrito de compras:
$_SESSION['carrito'] = [
    [
        'id'       => 1,          // ID del producto
        'nombre'   => 'iPhone 14', // Nombre
        'precio'   => 1200.00,    // Precio unitario
        'cantidad' => 2,          // Cantidad
        'imagen'   => 'path/img.avif'
    ],
    // ... más productos
];

// Mensajes de notificación:
$_SESSION['success'] // Mensaje de éxito
$_SESSION['error']   // Mensaje de error
$_SESSION['info']    // Mensaje informativo
```

---

## 📊 Tabla de Bases de Datos

### usuarios
```sql
id INT PRIMARY KEY AUTO_INCREMENT
nombre VARCHAR(100)
email VARCHAR(100) UNIQUE
password VARCHAR(255) HASHED
telefono VARCHAR(20)
direccion TEXT
rol VARCHAR(20) DEFAULT 'usuario'
fecha_registro DATETIME
```

### productos
```sql
id INT PRIMARY KEY AUTO_INCREMENT
nombre VARCHAR(150)
descripcion TEXT
precio DECIMAL(10, 2)
stock INT
imagen VARCHAR(255)
categoria_id INT FOREIGN KEY
```

### pedidos
```sql
id INT PRIMARY KEY AUTO_INCREMENT
usuario_id INT FOREIGN KEY
fecha DATETIME
total DECIMAL(10, 2)
estado VARCHAR(50) -- 'Pendiente de Pago', 'Pagado', 'Cancelado'
telefono VARCHAR(20)
direccion TEXT
metodo_pago VARCHAR(50)
fecha_pago DATETIME
```

### detalle_pedidos
```sql
pedido_id INT FOREIGN KEY
producto_id INT FOREIGN KEY
cantidad INT
precio_unitario DECIMAL(10, 2)
PRIMARY KEY (pedido_id, producto_id)
```

---

## 🛡️ Seguridad

### Prepared Statements (Todos los archivos)
```php
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
// ✅ Protege contra SQL Injection
```

### Hash de Contraseña
```php
// Al registrar:
$password_hash = password_hash($password, PASSWORD_BCRYPT);

// Al verificar:
if (password_verify($input_password, $password_hash)) {
    // Contraseña correcta
}
```

### Validación de Sesión
```php
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
// ✅ En todas las páginas protegidas
```

---

## 🔧 Archivos Clave

### Autenticación
- `login.php` - Formulario de login
- `register.php` - Formulario de registro
- `validar_login.php` - Valida credenciales (POST)
- `procesar_registro.php` - Procesa registro (POST)
- `logout.php` - Cierra sesión

### Tienda
- `index.php` - Página de inicio
- `productos.php` - Todos los productos
- `detalle_producto.php` - Detalles de un producto
- `carrito.php` - Ver/modificar carrito
- `header.php` - Menú y navegación
- `footer.php` - Pie de página

### Pago
- `finalizar_compra.php` - Resumen y envío
- `procesar_pedido.php` - Crear orden en BD
- `simular_pago_tarjeta.php` - Formulario tarjeta
- `procesar_pago_tarjeta.php` - Procesa pago tarjeta
- `pago_yape.php` - Mostrar QR Yape
- `confirmar_pago_yape.php` - Confirmar Yape
- `pedido_exito.php` - Página de éxito

### Admin
- `/admin/login.php` - Login admin
- `/admin/dashboard.php` - Panel principal
- `/admin/productos/index.php` - Lista productos
- `/admin/usuarios/index.php` - Lista usuarios
- `/admin/categorias/index.php` - Lista categorías

---

## 🎨 Clases Bootstrap Importantes

```html
<!-- Botones -->
<button class="btn btn-primary">Primario</button>
<button class="btn btn-success">Éxito</button>
<button class="btn btn-danger">Peligro</button>

<!-- Alertas -->
<div class="alert alert-success">✅ Éxito</div>
<div class="alert alert-danger">❌ Error</div>
<div class="alert alert-info">ℹ️ Información</div>

<!-- Grid -->
<div class="row">
    <div class="col-md-6">Mitad izquierda</div>
    <div class="col-md-6">Mitad derecha</div>
</div>

<!-- Tablas -->
<table class="table table-striped">
    <tr>...</tr>
</table>
```

---

## 📝 Ejemplos de Código Común

### Obtener usuario actual
```php
$usuario_id = $_SESSION['usuario_id'];
$usuario = $_SESSION['usuario'];
$email = $_SESSION['email'];
```

### Agregar al carrito
```php
$_SESSION['carrito'][] = [
    'id' => $producto_id,
    'nombre' => $nombre,
    'precio' => $precio,
    'cantidad' => 1,
    'imagen' => $imagen
];
```

### Calcular total carrito
```php
$total = 0;
foreach ($_SESSION['carrito'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}
```

### Usar transacción SQL
```php
$conn->begin_transaction();
try {
    // Operación 1
    // Operación 2
    $conn->commit();
} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}
```

---

## 🧪 Testing Rápido

### Verificar sesión
```php
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
```

### Verificar carrito
```php
echo 'Items: ' . count($_SESSION['carrito'] ?? []);
```

### Verificar conexión BD
```php
if ($conn->ping()) {
    echo "✅ BD conectada";
} else {
    echo "❌ BD desconectada";
}
```

---

## 🐛 Errores Comunes

| Error | Causa | Solución |
|-------|-------|----------|
| "Headers already sent" | include/echo antes de header() | Mover header() antes |
| "Undefined variable" | Variable no inicializada | Agregar isset() o inicializar |
| "SQL Error" | Sintaxis SQL incorrecta | Revisar prepared statement |
| "Session not working" | Falta session_start() | Agregar session_start() al inicio |
| "Carrito vacío" | No hay $_SESSION['carrito'] | Inicializar array |

---

## 📱 Responsive Design

```css
/* Mobile First */
/* 0-576px (por defecto) */

/* @media (min-width: 576px) -> Small (sm) */
/* @media (min-width: 768px) -> Medium (md) */
/* @media (min-width: 992px) -> Large (lg) */
/* @media (min-width: 1200px) -> Extra Large (xl) */

/* Ejemplo Bootstrap */
<div class="col-12 col-md-6 col-lg-4">
    <!-- Ancho completo en móvil, mitad en tablet, un tercio en desktop -->
</div>
```

---

## 🚀 Deployment Checklist

- [ ] Cambiar contraseña BD
- [ ] Usar valores reales en conexion.php
- [ ] Desactivar debug mode
- [ ] Revisar .htaccess
- [ ] Hacer backup BD
- [ ] Probar con HTTPS
- [ ] Revisar permisos de archivos
- [ ] Configurable correos (si es necesario)

---

**Última revisión:** 2024
**Versión:** 1.0 Final
