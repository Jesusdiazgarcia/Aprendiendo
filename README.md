# 🛍️ FCStore - Tienda Online de Camisetas de Fútbol

## 📋 Descripción del Proyecto

FCStore es una tienda online completa desarrollada en PHP que permite a los usuarios comprar camisetas de fútbol oficiales. El proyecto incluye un sistema de autenticación, gestión de productos, carrito de compras y panel de administración.

## ✨ Características Principales

### 🔐 Sistema de Usuarios
- **Registro e inicio de sesión** con validación de datos
- **Roles diferenciados**: Cliente y Administrador
- **Gestión de sesiones segura** con hash de contraseñas
- **Perfiles de usuario** con información personal

### 🛍️ Catálogo de Productos
- **Listado con paginación** (5 productos por página)
- **Búsqueda por nombre** y filtros por categoría
- **Galería de imágenes dinámica** con miniaturas
- **Información detallada**: Precio, stock, descripción
- **Sistema de promociones** con descuentos automáticos

### 🛒 Carrito de Compras
- **Agregar productos** con talla y cantidad
- **Gestión de stock** en tiempo real
- **Resumen de compra** con cálculos automáticos
- **Interfaz intuitiva** con feedback visual

### ⚙️ Panel de Administración
- **CRUD completo** de productos
- **Subida de imágenes** con validación
- **Gestión de promociones** y stock
- **Interfaz moderna** con modales Bootstrap
- **Actualización dinámica** con AJAX

### 🎨 Diseño y UX
- **Diseño responsive** con Bootstrap 5.3.0
- **CSS personalizado** con efectos modernos
- **JavaScript centralizado** y optimizado
- **Iconografía Font Awesome**
- **Animaciones suaves** y transiciones

## 🛠️ Tecnologías Utilizadas

### Backend
- **PHP 8.1+** - Lógica del servidor
- **MySQL** - Base de datos
- **Prepared Statements** - Seguridad SQL

### Frontend
- **HTML5** - Estructura semántica
- **CSS3** - Estilos y animaciones
- **JavaScript ES6+** - Interactividad
- **Bootstrap 5.3.0** - Framework CSS
- **Font Awesome 6.4.0** - Iconos

### Optimizaciones
- **Carga diferida de imágenes** (lazy loading)
- **Compresión Gzip** para archivos estáticos
- **Caché del navegador** configurado
- **JavaScript centralizado** en un solo archivo

## 📁 Estructura del Proyecto

```
FCStore/
├── 📄 Archivos PHP principales
│   ├── index.php          # Página principal con catálogo
│   ├── producto.php       # Detalle de producto
│   ├── boleta.php         # Carrito de compras
│   ├── login.php          # Autenticación
│   ├── registro.php       # Registro de usuarios
│   ├── dashboard_admin.php # Panel de administración
│   └── catalogo.php       # Catálogo completo
├── 📁 assets/
│   ├── css/style.css      # Estilos personalizados
│   ├── js/mis_scripts.js  # JavaScript centralizado
│   └── img/               # Imágenes de productos
├── 📄 Archivos de configuración
│   ├── conexion.php       # Conexión a base de datos
│   ├── cabecera.php       # Header común
│   ├── pie.php           # Footer común
│   └── menu.php          # Navegación
└── 📄 Configuración de despliegue
    ├── netlify.toml      # Configuración Netlify
    └── README.md         # Documentación
```

## 🚀 Instalación y Configuración

### Requisitos Previos
- PHP 8.1 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/tu-usuario/FCStore.git
   cd FCStore
   ```

2. **Configurar la base de datos**
   - Crear una base de datos MySQL llamada `fcstore`
   - Importar la estructura desde `database.sql`

3. **Configurar la conexión**
   - Editar `conexion.php` con tus credenciales de BD

4. **Configurar el servidor web**
   - Apuntar el DocumentRoot al directorio del proyecto

## 🔧 Configuración de Base de Datos

### Tablas Principales

#### `usuarios`
- Gestión de usuarios y autenticación
- Roles: cliente/admin
- Información personal completa

#### `productos`
- Catálogo de productos
- Categorías: local, visitante, alternativa
- Sistema de promociones

#### `compras`
- Carrito de compras
- Historial de productos agregados

## 🎯 Funcionalidades Destacadas

### 🔍 Búsqueda Inteligente
- Filtros por categoría y nombre
- Paginación automática
- Resultados en tiempo real

### 🖼️ Galería de Imágenes
- Múltiples vistas por producto
- Carga diferida para optimización
- Efectos hover y zoom

### 📱 Diseño Responsive
- Adaptable a móviles, tablets y desktop
- Navegación optimizada para touch
- Menús colapsables

### ⚡ Optimizaciones de Rendimiento
- Lazy loading de imágenes
- JavaScript centralizado
- Compresión de archivos estáticos
- Caché configurado

## 🔒 Seguridad Implementada

- **Hash de contraseñas** con `password_hash()`
- **Prepared statements** para prevenir SQL injection
- **Escape de datos** con `htmlspecialchars()`
- **Validación de sesiones** en todas las páginas
- **Control de acceso** por roles de usuario

## 📊 Métricas del Proyecto

- **+2,600 líneas de CSS** personalizado
- **+150 líneas de JavaScript** optimizado
- **+15 archivos PHP** bien estructurados
- **Sistema completo** de e-commerce
- **100% responsive** y accesible

## 🚀 Despliegue

### Netlify (Recomendado)
1. Conectar repositorio GitHub a Netlify
2. Configurar variables de entorno para BD
3. Desplegar automáticamente

### Alternativas
- **Vercel** - Con soporte PHP
- **Heroku** - Con add-on MySQL
- **Hostinger** - Hosting compartido

## 👨‍💻 Desarrollo

### Comandos Útiles
```bash
# Verificar sintaxis PHP
php -l archivo.php

# Optimizar imágenes
# Usar herramientas como TinyPNG o ImageOptim

# Minificar CSS/JS
# Usar herramientas online o build tools
```

## 📝 Notas del Desarrollador

Este proyecto demuestra:
- **Arquitectura MVC** simplificada
- **Buenas prácticas** de desarrollo web
- **Optimización de rendimiento**
- **Diseño responsive** moderno
- **Sistema de autenticación** seguro

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor:
1. Fork el proyecto
2. Crear una rama para tu feature
3. Commit tus cambios
4. Push a la rama
5. Abrir un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver `LICENSE` para más detalles.

---

**Desarrollado con ❤️ para demostrar habilidades en desarrollo web full-stack** 