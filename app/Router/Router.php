<?php 

include_once "Route.php";

final readonly class Router
{
    /** @param Route[] $routes */
    public function __construct(
        private readonly array $routes
    ) {
    }

    /**
     * Funcion principal que se encarga de la logica del Ruteador. 
     * Nos permite obtener la ruta a partir del URL, el metodo y los parametros enviados por el usuario.
     */
    public function resolve(string $url, string $method): void
    {
        // Buscamos la ruta
        $route = $this->filterRoute($url, $method);

        // Si no existe, error
        if (empty($route)) {
            throw new Exception('Invalid route');
        }

        // Cargamos el archivo correspondiente al controlador
        require $_SERVER["DOCUMENT_ROOT"].'/src/Controller/'.$route->controller();

        // Obtenemos los parametros
        $parameters = $this->getParameters($route, $url);

        // Instanciamos el controlador
        $controller = new ($route->className())();

        // Reflexión para saber el tipo de parámetro de start
        $reflection = new \ReflectionMethod($controller, 'start');
        $params = $reflection->getParameters();

        if (count($params) === 1 && $params[0]->getType() && $params[0]->getType()->getName() === 'array') {
            // Si espera un array, pásalo como array
            $controller->start($parameters);
        } else {
            // Si espera argumentos individuales, desempaqueta
            $controller->start(...$parameters);
        }
     


    }

    /**
     * Metodo que nos permite filtrar una ruta
     */
     private function filterRoute(string $url, string $method): ?Route
{
    foreach ($this->routes as $route) {
        // Convertir ruta con {param} a expresión regular
        $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^\/]+)', $route->url());
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $url, $matches) && $method === $route->method()) {
            // Removemos el primer elemento que es la coincidencia completa
            array_shift($matches);

            // Validamos parámetros usando el método original
            if ($this->validateParameters($matches, $route->parameters())) {
                return $route;
            }
        }
    }

    return null;
}


    /**
     * Metodo que nos permite obtener los parametros a partir de la URL seleccionada
     * Example: domain/1/2 -> [1, 2]
     */
    private function getParameters(Route $route, string $url): array
{
    $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^\/]+)', $route->url());
    $pattern = '#^' . $pattern . '$#';

    if (preg_match($pattern, $url, $matches)) {
        array_shift($matches);
        return $matches;
    }

    return [];
}


    /**
     * Metodo que nos permite validar si los parametros ingresados por el usuario coinciden con la configuracion de la ruta
     */
    private function validateParameters(array $urlParameters, array $routeParameters): bool 
    {
        // Si los parametros ingresados en el URL no coinciden con la cantidad de parametros esperados por la ruta, retornamos false
        if (sizeof($urlParameters) !== sizeof($routeParameters)) {
            return false;
        }

        $validParams = 0;
        // Recorremos cada uno de los parametros enviados por el usuario
        for ($i = 0; $i < sizeof($routeParameters); $i++) {
            $type = $routeParameters[$i]['type'] ?? 'string';

            // Si el tipo de parametro que esperamos es un tipo INT, y el usuario nos envia un string 
            // Example: 'Hola'
            // NO lo declaramos como valido y pasamos hacia el siguiente parametro
            if ($type === 'int' && (int) $urlParameters[$i] === 0) {
                // Con esta linea le decimos a PHP que no siga recorriendo este bucle y se vaya al siguiente
                continue;
            } 

            // En el caso de que todo este OK, el parametro se considera como valido
            $validParams++;
        }

        // Si la cantidad de parametros validos es diferente a la cantidad de parametros esperados por la ruta, retornamos false
        if ($validParams !== sizeof($urlParameters)) {
            return false;
        }

        // Si todo esta OK, return true
        return true;
    }
}