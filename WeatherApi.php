<?php 

require_once "Api.php";

/**
 * Api para la obtención del tiempo para una ciudad.
 * Implementa la clase genérica para el uso de Apis Rest
 * 
 * Información basada en https://developer.here.com/api-explorer/rest/auto_weather
 * 
 * @author Héctor Martín Solís <hecmartinsol@gmail.com>
 * @api
 * @version 1.0.0
 */
class WeatherApi extends Api
{
    /**
     * Identificador del cliente
     * @var string
     */
    private $app_id = "OzHoFo1Qyr4VEdVUxaIP";
    /**
     * Identificador del cliente
     * @var string
     */
    private $app_code = "bUwnXelnCq8TFCgiuWm9Og";

    /**
     * Tipo de productos soportados
     * @var array
     */
    private $validProducts = array(
        "observation", 
        "forecast_7days", 
        "forecast_7days_simple", 
        "forecast_hourly", 
        "forecast_astronomy", 
        "selected", 
        "nws_alerts"
    );

    /**
     * Producto a consultar
     * @var string
     */
    private $product;
    /**
     * Nombre de la ciudad a consultar
     * @var string
     */
    private $name;

    public function __construct()
    {
        $this->url = "https://weather.api.here.com/weather/1.0/report.json";
        $this->method = "get";
        $this->headers = array("Content-Type: application/json");
    }

    ######     GETTERS     ######
    public function getProduct() : string
    {
        return $this->product;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getValidProducts()
    {
        return $this->validProducts;
    }


    ######     SETTERS     ######
    public function setProduct(
        string $product = ""
    ) : bool
    {
        $product = strtolower($product);
        if (!in_array($product, $this->validProducts)) {
            return false;
        }
        
        $this->product = $product;
        return true;
    }

    public function setName(
        string $name = ""
    ) : bool
    {
        $this->name = $name;
        return true;
    }


    ######     FUNCTIONS     ######

    
    /**
     * Dados los parametros de producto y nombre, realiza una petición al API con los métodos heredados
     * 
     * @return string|array|object
     */
    public function getForecast()
    {
        $parameters = array(
            "app_id" => $this->app_id,
            "app_code" => $this->app_code,
            "product" => $this->product,
            "name" => $this->name
        );

        # $result = $this->makeCurl($parameters);
        $result = $this->makeRequest($parameters);

        return $result;
    }
}