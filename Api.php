<?php 

/**
 * Clase genérica para hacer peticiones a un API o Webservice
 * 
 * @author Héctor Martín Solís <hecmartinsol@gmail.com>
 * @api
 * @version 1.0.0
 */
class Api
{
    /**
     * Formato en el que se quiere que se devuelvan los datos obtenidos tras hacer la petición
     * @var string
     */
    protected $outputFormat = "string";
    /**
     * Formatos de salidasoportados
     * @var array
     */
    protected $validOutputFormats = array('string', 'array', 'object');

    /**
     * Método con el que se realizará la petición
     * @var string
     */
    protected $method = "get";
    /**
     * Métodos soportados
     * @var array
     * @todo metodos put/delete
     */
    protected $validMethods = array('get', 'post');

    /**
     * Array de strings que representan las cabeceras que se enviarán en la petición
     * @var array
     */
    protected $headers = array();
    /**
     * @var string
     * Dirección sobre la que se harán las peticiones
     */
    protected $url = "";

    /**
     * @var array
     * Todos los estados que puede tener la respuesta tras hacer la petición
     * Info obtenida de https://httpstatuses.com/
     */
    protected $allStatuses = array(
        # 1×× - Informational
        100 => "Continue",
        101 => "Switching Protocols",
        102 => "Processing",
        
        # 2×× - Success
        200 => "OK",
        201 => "Created",
        202 => "Accepted",
        203 => "Non-authoritative Information",
        204 => "No Content",
        205 => "Reset Content",
        206 => "Partial Content",
        207 => "Multi-Status",
        208 => "Already Reported",
        226 => "IM Used",
        
        # 3×× - Redirection
        300 => "Multiple Choices",
        301 => "Moved Permanently",
        302 => "Found",
        303 => "See Other",
        304 => "Not Modified",
        305 => "Use Proxy",
        307 => "Temporary Redirect",
        308 => "Permanent Redirect",
        
        # 4×× - Client Error
        400 => "Bad Request",
        401 => "Unauthorized",
        402 => "Payment Required",
        403 => "Forbidden",
        404 => "Not Found",
        405 => "Method Not Allowed",
        406 => "Not Acceptable",
        407 => "Proxy Authentication Required",
        408 => "Request Timeout",
        409 => "Conflict",
        410 => "Gone",
        411 => "Length Required",
        412 => "Precondition Failed",
        413 => "Payload Too Large",
        414 => "Request-URI Too Long",
        415 => "Unsupported Media Type",
        416 => "Requested Range Not Satisfiable",
        417 => "Expectation Failed",
        418 => "I'm a teapot",
        421 => "Misdirected Request",
        422 => "Unprocessable Entity",
        423 => "Locked",
        424 => "Failed Dependency",
        426 => "Upgrade Required",
        428 => "Precondition Required",
        429 => "Too Many Requests",
        431 => "Request Header Fields Too Large",
        444 => "Connection Closed Without Response",
        451 => "Unavailable For Legal Reasons",
        499 => "Client Closed Request",
        
        # 5×× - Server Error
        500 => "Internal Server Error",
        501 => "Not Implemented",
        502 => "Bad Gateway",
        503 => "Service Unavailable",
        504 => "Gateway Timeout",
        505 => "HTTP Version Not Supported",
        506 => "Variant Also Negotiates",
        507 => "Insufficient Storage",
        508 => "Loop Detected",
        510 => "Not Extended",
        511 => "Network Authentication Required",
        599 => "Network Connect Timeout Error"
    );
    /**
     * Estado de la respuesta obtenida
     * @var int
     */
    protected $status = 501;
    /**
     * Respuesta de la petición realizada
     * @var array|string|object
     */
    protected $response = NULL;


    public function __construct(
        string $url = "")
    {
        $this->url = $url;
    }


    ######     GETTERS     ######
    public function getOutputFormat() : string
    {
        return $this->outputFormat;
    }
    
    public function getMethod() : string
    {
        return $this->method;
    }

    public function getUrl() : string
    {
        return $this->url;
    }

    public function getHeaders() : array
    {
        return $this->headers;
    }

    public function getStatus() : int
    {
        return $this->status;
    }
    public function getAllStatuses() : array
    {
        return $this->allStatuses;
    }
    public function getResponse()
    {
        return $this->response;
    }

    ######     SETTERS     ######
    public function setMethod(
        string $method = "get") : bool
    {
        $method = strtolower($method);
        if (!in_array($method, $this->validMethods))
        {
            return false;
        }

        return  $this->method = $method;
    }

    public function setOutputFormat(
        string $outputFormat = "string") : bool
    {
        $outputFormat = strtolower($outputFormat);
        if (!in_array($outputFormat, $this->validOutputFormats))
        {
            return false;
        }

        return  $this->outputFormat = $outputFormat;
    }

    public function setUrl(
        string $url = ""
    ) : bool
    {
        return  $this->url = $url;
    }

    public function setHeaders(
        array $headers = array()
    ) : bool
    {
        return  $this->headers = $headers;
    }

    private function setStatus(
        array $headers = array())
    {
        foreach( $headers as $header )
        {
            if (strpos($header, "HTTP/") !== FALSE)
            {
                $code = explode(" ", $header)[1];
                if (array_key_exists($code, $this->allStatuses))
                {
                    return $this->status = intval($code);
                }
            }
        }

        return $this->status = 501;
    }

    ################################################################################################
    /**
     * Añade una cabecera a las que ya se tienen establecidas
     * 
     * @param string|string $header 
     * @return type
     */
    public function addHeader(
        string $header = ""
    ) : bool
    {
        return array_push($this->headers, $header);
    }


    /**
     * Llamada mediante CURL
     * 
     * @param array|array $parameters 
     * @return type
     * @todo
     */
    // public function makeCurl(
    //     array $parameters = array()
    // )
    // {
    //     $url = $this->url . (strpos($this->url, '?') === FALSE ? '?' : '');
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_HEADER, 0);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_TIMEOUT, 4);
    //     if ($this->method == "post"){
    //         curl_setopt($ch, CURLOPT_POST, 1);
    //         curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
    //         curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
    //         curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
    //     }
    //     else if ($this->method == "get"){
    //         $url = $url . http_build_query($parameters);
    //         curl_setopt($ch, CURLOPT_URL, $url);
    //     }
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
    //     if(!$result = curl_exec($ch)) 
    //     { 
    //         trigger_error(curl_error($ch)); 
    //     } 
    //     curl_close($ch); 
    //     return $result; 
    // }


    /**
     * Realiza la petición al API con los parámetros previamente establecidos
     * y mandando los parámetros de la petición
     * 
     * @param array|array $parameters 
     * @return type
     */
    public function makeRequest(
        array $parameters = array()
    )
    {
        $opts = array('http' =>
            array(
                'method'  => strtoupper($this->method),
                'header'  => $this->headers,
                'content' => http_build_query($parameters)
            )
        );

        $context  = stream_context_create($opts);

        $url = $this->url;
        if ($this->method == "get") {
            $url = $this->url . (strpos($this->url, "?") === false ? "?" : "") . http_build_query($parameters);
        }

        $this->response = file_get_contents($url, false, $context);
        $this->setStatus($http_response_header);

        return $this->retunFormatted();
    }

    /**
     * Formatea la salida obtenida del API al formato prestablecido
     * 
     * @return array|object|string
     */
    private function retunFormatted()
    {
        switch ($this->outputFormat) {
            
            case 'array': 
                $this->response = json_decode($this->response, true);
            break;
            case 'object': 
                $this->response = json_decode($this->response);
            break;
            
            case 'string': break;

            default:  break;
        }

        return $this->response;
    }

}
 ?>