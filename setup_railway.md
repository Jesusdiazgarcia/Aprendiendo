# Configuración de Railway - FCStore

## Paso 1: Instalar Railway CLI
```bash
npm install -g @railway/cli
```

## Paso 2: Iniciar sesión
```bash
railway login
```

## Paso 3: Conectar al proyecto
```bash
railway link
```

## Paso 4: Conectar a la base de datos
```bash
railway connect
```

## Paso 5: Ejecutar el script SQL
Una vez conectado, ejecuta:
```sql
source database.sql;
```

---

## Opción Alternativa: Usando phpMyAdmin

### Paso 1: Obtener credenciales
1. En Railway, ve al servicio MySQL
2. Copia las variables de entorno

### Paso 2: Conectar con phpMyAdmin
1. Ve a [phpMyAdmin](https://www.phpmyadmin.net/)
2. Usa las credenciales de Railway:
   - Host: MYSQL_HOST
   - Usuario: MYSQL_USER
   - Contraseña: MYSQL_PASSWORD
   - Puerto: MYSQL_PORT

### Paso 3: Crear base de datos
1. Crea una nueva base de datos llamada `fcstore`
2. Importa el archivo `database.sql`

---

## Opción C: Usando MySQL Workbench

1. Descarga [MySQL Workbench](https://dev.mysql.com/downloads/workbench/)
2. Crea una nueva conexión con las credenciales de Railway
3. Ejecuta el script `database.sql`

---

## Variables de Entorno para Netlify

Una vez que tengas las credenciales de Railway, añádelas a Netlify:

```
RAILWAY_ENVIRONMENT=production
MYSQL_HOST=tu-host-de-railway
MYSQL_USER=tu-usuario
MYSQL_PASSWORD=tu-password
MYSQL_DATABASE=fcstore
MYSQL_PORT=3306
``` 