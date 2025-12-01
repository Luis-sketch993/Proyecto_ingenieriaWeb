# 🛍️ Flujo de Pago - Documentación Completa

## 📋 Descripción General

El sistema de pago para "Second Use" incluye dos métodos de pago integrados:
1. **Tarjeta de Crédito/Débito** - Simulación de pasarela de pago
2. **Yape/Plin** - Confirmación manual con código QR

---

## 🔄 Flujo de Proceso Detallado

### **Fase 1: Carrito y Finalización**
```
1. Usuario en carrito.php
   ↓
2. Click en "Finalizar Compra"
   ↓
3. → finalizar_compra.php
   - Valida sesión (usuario_id, usuario)
   - Muestra resumen de productos
   - Muestra opciones de pago (radio buttons)
   - Solicita: Teléfono, Dirección
```

### **Fase 2: Creación del Pedido**
```
4. Usuario selecciona método de pago y hace click en "Pagar"
   ↓
5. POST a → procesar_pedido.php
   - Inicia transacción SQL
   - Crea registro en tabla 'pedidos' (estado: "Pendiente de Pago")
   - Inserta detalles en 'detalle_pedidos'
   - Actualiza stock (resta cantidad)
   - Confirma transacción
   - Obtiene pedido_id
```

### **Fase 3a: Pago con Tarjeta**
```
Si método = 'pasarela':
6. → simular_pago_tarjeta.php?pedido={id}
   - Muestra formulario con campos:
     • Número de tarjeta (16 dígitos)
     • Nombre del titular
     • Fecha de expiración (MM/AA)
     • CVV (3-4 dígitos)
   - Muestra total a pagar
   
7. Usuario completa formulario y hace click en "Pagar"
   ↓
8. POST a → procesar_pago_tarjeta.php
   - Valida formato de tarjeta
   - Verifica que el pedido existe y pertenece al usuario
   - Simula procesamiento de pago (sleep 1 segundo)
   - Actualiza estado de pedido a "Pagado"
   - Establece fecha_pago
   - Redirige a → pedido_exito.php
```

### **Fase 3b: Pago con Yape/Plin**
```
Si método = 'yape':
6. → pago_yape.php?pedido={id}
   - Muestra código QR (image/yape2.jpg)
   - Muestra instrucciones paso a paso
   - Muestra total a pagar
   - Muestra botón "Ya pagué con Yape/Plin - Confirmar"
   
7. Usuario escanea QR con su celular
   - Abre app de Yape/Plin
   - Confirma monto
   - Realiza transferencia
   
8. Usuario hace click en "Confirmar Pago"
   ↓
9. POST a → confirmar_pago_yape.php
   - Valida que el pedido existe y pertenece al usuario
   - Verifica que no haya sido pagado ya
   - Actualiza estado de pedido a "Pagado"
   - Redirige a → pedido_exito.php
```

### **Fase 4: Confirmación Final**
```
10. → pedido_exito.php?pedido={id}&metodo={tarjeta|yape}
    - Muestra confirmación de éxito
    - Muestra detalles del pedido:
      • Número de pedido
      • Total pagado
      • Método de pago usado
      • Estado del pedido
      • Email de confirmación
    - Limpia el carrito ($_SESSION['carrito'])
    - Muestra botón "Volver a la Tienda"
```

---

## 🔐 Seguridad y Validaciones

### **Validaciones en cada paso:**

| Archivo | Validaciones |
|---------|-------------|
| `finalizar_compra.php` | ✅ usuario_id existe, ✅ carrito no vacío |
| `procesar_pedido.php` | ✅ usuario_id, ✅ carrito, ✅ teléfono, ✅ dirección, ✅ método pago |
| `simular_pago_tarjeta.php` | ✅ usuario_id, ✅ pedido_id válido |
| `procesar_pago_tarjeta.php` | ✅ usuario_id, ✅ tarjeta 16 dígitos, ✅ CVV 3-4 dígitos, ✅ fecha MM/AA, ✅ pedido pertenece a usuario |
| `pago_yape.php` | ✅ usuario_id, ✅ pedido_id válido |
| `confirmar_pago_yape.php` | ✅ usuario_id, ✅ pedido_id válido, ✅ pedido pertenece a usuario |
| `pedido_exito.php` | ✅ pedido_id válido, ✅ limpia carrito |

---

## 💾 Estructura de Base de Datos

### Tabla `pedidos`
```sql
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    fecha DATETIME,
    total DECIMAL(10, 2),
    estado VARCHAR(50) -- 'Pendiente de Pago', 'Pagado', 'Cancelado'
    telefono VARCHAR(20),
    direccion TEXT,
    metodo_pago VARCHAR(50), -- 'Tarjeta de Crédito', 'Yape', etc.
    fecha_pago DATETIME
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
```

### Tabla `detalle_pedidos`
```sql
CREATE TABLE detalle_pedidos (
    pedido_id INT,
    producto_id INT,
    cantidad INT,
    precio_unitario DECIMAL(10, 2),
    PRIMARY KEY (pedido_id, producto_id),
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);
```

---

## 📱 Sesión de Usuario

### Variables de sesión requeridas:
```php
$_SESSION['usuario_id']    // ID del usuario (CRÍTICO)
$_SESSION['usuario']       // Nombre del usuario
$_SESSION['email']         // Email del usuario
$_SESSION['rol']           // Rol (admin, usuario)
$_SESSION['carrito']       // Array con productos
```

### Estructura del carrito:
```php
$_SESSION['carrito'] = [
    [
        'id' => 1,
        'nombre' => 'Producto X',
        'precio' => 100.00,
        'cantidad' => 2,
        'imagen' => 'ruta/imagen.avif'
    ],
    // ... más productos
];
```

---

## 🎨 Interfaces Visuales Mejoradas

### **Diseño de Tarjetas de Pago**
- Fondo gradiente (morado/azul)
- Tarjeta blanca centrada con sombra
- Animación de entrada (slideIn)
- Campos con diseño moderno

### **Página de Éxito**
- Icono grande de éxito (verde)
- Información clara del pedido
- Detalles en grid responsivo
- Botón "Volver a la Tienda"

---

## 🧪 Testing

### Para probar con Tarjeta:
1. Ir a finalizar_compra.php
2. Seleccionar "Tarjeta de Crédito"
3. Usar cualquier número de 16 dígitos
4. Completar otros campos
5. Verificar que se llegue a pedido_exito.php

### Para probar con Yape:
1. Ir a finalizar_compra.php
2. Seleccionar "Yape/Plin"
3. En pago_yape.php, hacer click en "Confirmar"
4. Verificar que se llegue a pedido_exito.php

---

## 📝 Archivos Clave

```
finalizar_compra.php          → Formulario de pedido + selección de pago
├─ procesar_pedido.php        → Crear pedido en BD + decidir ruta
├─ simular_pago_tarjeta.php   → Formulario de tarjeta
│  └─ procesar_pago_tarjeta.php → Procesar pago de tarjeta
├─ pago_yape.php              → Mostrar QR
│  └─ confirmar_pago_yape.php → Confirmar pago Yape
└─ pedido_exito.php           → Confirmación final
```

---

## ✨ Características Adicionales

✅ Transacciones SQL (begin_transaction, commit, rollback)
✅ Gestión de stock (reserva en orden, actualiza en pago)
✅ Validación de datos en múltiples capas
✅ Mensajes de error descriptivos
✅ Interfaz responsive para móviles
✅ Códigos QR funcionales para Yape/Plin

---

## 🔗 URLs de Referencia

| Acción | URL |
|--------|-----|
| Ver carrito | `/carrito.php` |
| Finalizar compra | `/finalizar_compra.php` |
| Pagar con tarjeta | `/simular_pago_tarjeta.php?pedido=1` |
| Pagar con Yape | `/pago_yape.php?pedido=1` |
| Confirmación final | `/pedido_exito.php?pedido=1&metodo=tarjeta` |

---

## 📞 Soporte

Para reportar problemas o sugerencias, consulta los logs de error en `error_log` del servidor.
