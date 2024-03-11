<?php
require_once 'lib/Log.php';

/**
 * Eisi API Environment enum class
 */
enum EisiApiEnvironment: string
{
    case Testing = 'Testing';
    case Producion = 'Production';
}

/**
 * Eisi API URL enum class
 */
enum EisiApiUrl: string
{
    case Testing = 'http://apitest.eisisoft.com';
    case Production = 'https://api.eisisoft.com';
}

/**
 * Eisi API Language enum class
 */
enum EisiApiLang: string
{
    case Espanol = 'es';
    case English = 'en';
    case Catala = 'ca';
    case Francoise = 'fr';
    case Italiano = 'it';
    case Portugues = 'pt';
    case Deutsch = 'de';
}

/**
 * Eisi API Endpoint enum class
 */
enum EisiApiEndpoint: string
{
    case LocationTree = '/tasksapi/locationtree';
    case ProblemsTree = '/tasksapi/problemstree';
    case CreateTask = '/tasksapi/create_task';
    case GetDepartmentsForSite = '/tasksapi/getDepartmentsForSite';
    case GetHistory = '/tasksapi/get_history';
}

/**
 * Eisi API Method enum class
 */
enum EisiApiMethod: string
{
    case GET = 'GET';
    case POST = 'POST';
}

/**
 * Eisi API Departments enum class
 */
enum EisiApiDepartment: string
{
    case MANTENIMIENTO = 'M';
    case LIMPIEZA = 'L';
    case IT = 'IT';
    case GEX = 'GEX';
    case SEGURIDAD = 'SEGURIDAD';
}

/**
 * Eisi API Tree Types enum class
 */
enum EisiApiTreeType: string
{
    case HOTEL = 'H';
    case OTRASZONAS = 'OZ';
}

/**
 * Eisi API class
 */
class EisiApi
{
    /** Properties */
    private string $apiUrl;
    private EisiApiEnvironment $env;
    private int $site;
    private EisiApiLang $lang;
    private string $hashedPassword;
    private bool $ready;
    private Log $log;

    /**
     * Constructor
     * @param EisiApiEnvironment $env
     * @param int $site
     * @param EisiApiLang $lang
     * @param string $password
     * @param LogType $logType
     */
    public function __construct(
        EisiApiEnvironment $env = EisiApiEnvironment::Producion,
        int $site = 0,
        EisiApiLang $lang = EisiApiLang::Espanol,
        ?string $password = NULL,
        LogType $logType = LogType::TextLog
    ) {
        $this->env = $env;
        $this->apiUrl = self::setApiUrl($env);
        $this->site = $site;
        $this->lang = $lang;
        $this->hashedPassword = self::getHash($site, $password);
        $this->log = new $logType->value();
        $this->ready = self::setApiIsReady();
    }

    public function __destruct() {
        $this->log->log("EisiApi Destructor called");
    }

    /** Public Methods */

    /** Setters */
    /**
     * Set the API environment
     *
     * @return string
     */
    public function setApiEnv(EisiApiEnvironment $env)
    {
        $this->env = $env;
        $this->apiUrl = self::setApiUrl($env);
    }

    /**
     * Set the API Site
     */
    public function setApiSite(int $site)
    {
        $this->site = $site;
        $this->ready = self::setApiIsReady();
    }

    /**
     * Set the API Language
     */
    public function setApiLanguage(EisiApiLang $lang)
    {
        $this->lang = $lang;
    }

    /**
     * Sets the log message
     */
    public function log(string $message)
    {
        $this->log->log($message);
    }

    /**
     * Set de API hashed password
     */
    public function setHashedPassword(string $password)
    {
        $this->hashedPassword = self::getHash($this->site, $password);
        $this->ready = self::setApiIsReady();
    }

    /** Getters */
    /**
     * Get the API environment
     *
     * @return string
     */
    public function getApiEnv()
    {
        return $this->env->value;
    }

    /**
     * Get the API URL
     *
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * Get the API language
     *
     * @return string
     */
    public function getApiLang(): string
    {
        return $this->lang->value;
    }

    /**
     * Get the API site
     *
     * @return int
     */
    public function getApiSite(): int
    {
        return $this->site;
    }

    /**
     * Get if the API is ready
     *
     * @return bool
     */
    private function getApiIsReady(): bool
    {
        return $this->ready ? true : false;
    }

    /**
     * Get if the API is ready
     * human readable format
     *
     * @return string
     */
    public function getApiIsReadyHuman(): string
    {
        return $this->ready ? 'Yes!' : 'No...';
    }

    /**
     * Get the API hashed password
     *
     * @return string
     */
    public function getApiHashedPassword(): string
    {
        return $this->hashedPassword;
    }

    /** Private Methods */

    /**
     * Set the API URL
     *
     * @param EisiApiEnvironment $env
     *
     * @return string
     */
    private function setApiUrl(EisiApiEnvironment $env)
    {
        return match ($env) {
            EisiApiEnvironment::Testing => EisiApiUrl::Testing->value,
            EisiApiEnvironment::Producion => EisiApiUrl::Production->value,
        };
    }

    /**
     * Set if the API is ready
     *
     * @param string $password
     *
     * @return bool
     */
    private function setApiIsReady()
    {
        return (bool) $this->site > 0 && $this->hashedPassword !== 'NULL';
    }

    /** Aux Methods */

    /**
     * Set the hash
     *
     * @param int $site
     * @param string $password
     *
     * @return string
     */
    private function getHash(int $site, ?string $password)
    {
        if ($site === 0 || !$password || strlen($password) === 0) return 'NULL';
        return md5($password . $site);
    }

    /** Static Methods */

    /**
     * Create a task body for API createTask
     *
     * @param object|array $service_tag_list
     * @param string $habitacion
     * @param int $zona
     * @param int $problema
     * @param string $departamento
     * @param string $observaciones
     * @param array $urls_fotos
     * @param int $remitida_cliente
     * @param string $usuario
     *
     * @return array|bool
     */
    public function createTaskBody(
        object|array $service_tag_list,
        string $habitacion,
        int $zona,
        int $problema,
        string $departamento,
        string $observaciones,
        array $urls_fotos,
        int $remitida_cliente,
        string $usuario,
    ): array|bool {
        if (!$this->getApiIsReady()) return false;

        // if ($habitacion === '' && $zona === -1) return false;
        if (strlen($habitacion) === 0 && $zona < 0) return false;

        $eisi_tag_list = array(
            'problema' => $problema,
            'departamento' => $departamento,
            'observaciones' => $observaciones,
            'urls_fotos' => $urls_fotos,
            'remitida_cliente' => $remitida_cliente,
            'usuario' => $usuario,
        );

        if ($habitacion !== '') $eisi_tag_list['habitacion'] = $habitacion;
        else if ($zona !== -1) $eisi_tag_list['zona'] = $zona;

        return [
            'site' => $this->site,
            'service_tag_list' => $service_tag_list,
            'eisi_tag_list' => $eisi_tag_list,
            'password' => $this->hashedPassword
        ];
    }

    /** General Usage Methods */

    /**
     *
     * Make an API call
     *
     * @param EisiApiEndpoint $endpoint
     * @param array $params
     * @param array $body
     *
     * @return object|array|string
     */
    public function makeApiCall(
        EisiApiEndpoint $endpoint = EisiApiEndpoint::LocationTree,
        array $params = [],
        array $body = [],
        int $timeout = 30
    ): object|array|string {
        if (!$this->ready) return false;

        $url = $this->apiUrl . $endpoint->value;

        if ($endpoint === EisiApiEndpoint::CreateTask) {
            $method = EisiApiMethod::POST;
        } else {
            $method = EisiApiMethod::GET;
            $url .= '?' . http_build_query([
                'site' => $this->site,
                'lang' => $this->lang->value,
                'password' => $this->hashedPassword,
                ...$params
            ]);
        }

        $this->log($method->value . ' :: ' . $url . " :: Curl-Timeout: " . $timeout . " s.");

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        if ($method === EisiApiMethod::POST) {
            // if (count($body) === 0) return [];
            return json_encode($body);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->log("RESPONSE CODE :: " . $httpcode);

        return json_decode($response);
    }
}