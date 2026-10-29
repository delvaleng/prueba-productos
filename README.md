# API de Productos (Prueba Backend)

**Desarrollador:** Ing. de Sistemas Gloribel Delgado  

---

API RESTful en Laravel Framework 10.50.4 para gestionar productos y sus precios en varias divisas.
 
## Requisitos
- PHP 8.1 o superior con pdo_mysql (y pdo_sqlite para poder ejecutar las pruebas)
- Composer 2
- MySQL 8
 
## Instalación
```bash
git clone git@github.com:delvaleng/prueba-productos.git
cd prueba-productos
composer install
cp .env.example .env
php artisan key:generate
# configurar DB_* en .env y crear la base prueba_productos
php artisan migrate --seed
php artisan serve
```
 
Usuario de prueba: `admin@prueba.test` / `password`
 
## Autenticación
`POST /api/login` devuelve un token. Enviarlo en cada petición:
`Authorization: Bearer <token>`
 
## Endpoints
| Método | Ruta | Descripción |
|---|---|---|
| POST | /api/login | Obtener token |
| POST | /api/logout | Eliminar token |
| GET | /api/products | Listar (paginado: per_page, search) |
| POST | /api/products | Crear |
| GET | /api/products/{id} | Ver con divisa y precios |
| PUT | /api/products/{id} | Actualizar (admite parcial) |
| DELETE | /api/products/{id} | Eliminar (borrado lógico) |
| GET | /api/products/{id}/prices | Listar precios |
| POST | /api/prices | Crear precio |
| GET | /api/currencies | Listar divisas |
| POST | /api/currencies | Crear divisa |
 
## Divisas y conversión
`exchange_rate` = unidades de la divisa por 1 USD.  
Si `POST /prices` no trae `price`, se calcula: `precio_base / tasa_base * tasa_destino`.
 
## Arquitectura
Controllers delgados -> Form Requests (validación) -> Service (lógica) -> Eloquent.  
Salida con API Resources. Ver `app/Services/ProductPriceService.php`.
 
## Seguridad
Sanctum, throttle, validación, `$fillable`, respuestas JSON sin detalles internos.
 
## Pruebas
```bash
php artisan test
```
 
## Documentación
Colección Postman en `docs/postman/` (importar colección y environment).
Tambien lo puedes encontrar en la siguiente url: 
`https://documenter.getpostman.com/view/6420287/2sBYHVwKHU`