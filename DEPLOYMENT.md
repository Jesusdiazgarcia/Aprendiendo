# Guía de Despliegue - FCStore

## Despliegue en Netlify + Railway

### Paso 1: Configurar Base de Datos en Railway

1. Ve a [Railway.app](https://railway.app) y crea una cuenta
2. Crea un nuevo proyecto
3. Añade un servicio MySQL
4. Una vez creado, ve a la pestaña "Variables" y copia las credenciales:
   - `MYSQL_HOST`
   - `MYSQL_USER` 
   - `MYSQL_PASSWORD`
   - `MYSQL_DATABASE`
   - `MYSQL_PORT`

### Paso 2: Crear la Base de Datos

1. En Railway, ve a la pestaña "Connect" de tu servicio MySQL
2. Usa el cliente MySQL o phpMyAdmin para conectarte
3. Ejecuta el script SQL para crear las tablas:

```sql
CREATE DATABASE IF NOT EXISTS fcstore;
USE fcstore;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'usuario') DEFAULT 'usuario',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    imagen VARCHAR(255),
    categoria VARCHAR(100),
    stock INT DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    producto_id INT,
    cantidad INT DEFAULT 1,
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);
```

### Paso 3: Desplegar en Netlify

1. Ve a [Netlify.com](https://netlify.com) y crea una cuenta
2. Conecta tu repositorio de GitHub
3. En la configuración del sitio, añade las variables de entorno:
   - `RAILWAY_ENVIRONMENT=production`
   - `MYSQL_HOST` (desde Railway)
   - `MYSQL_USER` (desde Railway)
   - `MYSQL_PASSWORD` (desde Railway)
   - `MYSQL_DATABASE` (desde Railway)
   - `MYSQL_PORT` (desde Railway)

### Paso 4: Configurar Dominio Personalizado (Opcional)

1. En Netlify, ve a "Domain settings"
2. Añade tu dominio personalizado
3. Configura los registros DNS según las instrucciones

### Paso 5: Verificar el Despliegue

1. Visita tu sitio en Netlify
2. Prueba el registro de usuarios
3. Prueba el login
4. Verifica que los productos se muestren correctamente

## Variables de Entorno Requeridas

```env
RAILWAY_ENVIRONMENT=production
MYSQL_HOST=tu-host-de-railway
MYSQL_USER=tu-usuario
MYSQL_PASSWORD=tu-password
MYSQL_DATABASE=fcstore
MYSQL_PORT=3306
```

## Solución de Problemas

### Error de Conexión a Base de Datos
- Verifica que las variables de entorno estén correctamente configuradas
- Asegúrate de que Railway esté funcionando
- Revisa los logs en Netlify

### Error 404 en Netlify
- Verifica que el archivo `netlify.toml` esté en la raíz del proyecto
- Asegúrate de que las redirecciones estén configuradas correctamente

### Problemas con Sesiones
- Netlify puede tener problemas con sesiones PHP
- Considera usar cookies o almacenamiento local para el carrito 