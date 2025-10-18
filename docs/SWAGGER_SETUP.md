# Instrucciones para integrar Swagger (L5-Swagger) - buenas prácticas

1. Instalar paquete (desde la raíz del proyecto):
    - composer require "darkaonline/l5-swagger"
2. Publicar configuración y vistas:
    - php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
3. Ajustes recomendados en config/l5-swagger.php:
    - 'default' => 'default'
    - 'documentations' => ['default' => ['paths' => ['docs' => storage_path('api-docs')]]]
    - Habilitar 'generate_always' = env('L5_SWAGGER_GENERATE_ALWAYS', false) (usar true solo en dev)
4. Generar JSON/OpenAPI:
    - php artisan l5-swagger:generate
    - La UI por defecto estará en /api/documentation
5. Buenas prácticas:
    - Mantener @OA\Info centralizado (puede colocarse en un archivo App\Http\Controllers\API\OpenApiController o en comentarios en un archivo dedicado).
    - No documentar ni exponer contraseñas ni secretos.
    - Usar securitySchemes (bearerAuth) y marcar endpoints que requieran autenticación.
    - Versionar la API (v1, v2) en la ruta y en @OA\Info.
    - Generar docs en CI/CD y subir artifacts o exponer solo en entornos seguros.
6. Flujo recomendado:
    - Anotar controladores y modelos con @OA tags y propiedades.
    - Ejecutar `php artisan l5-swagger:generate` en CI para actualizar docs.
    - Revisar y limpiar ejemplos y respuestas para que la documentación sea útil.
