<?php
require_once 'lib/EisiApi.php';

/** Log Class Testing */
// Usage example: TextLog
// $textLog = new TextLog();
// $textLog->log("1. API output to be logged as text");
// $textLog->log("2. API output to be logged as text");
// $textLog->log("3. API output to be logged as text");
// $textLog->log("4. API output to be logged as text");

// $textLog->showLog();

// Usage example: FileLog
// $fileLog = new FileLog();
// $fileLog->log("1. API output to be logged as file");
// $fileLog->log("2. API output to be logged as file");
// $fileLog->log("3. API output to be logged as file");
// $fileLog->log("4. API output to be logged as file");

// $fileLog->showLog();

/** End Log Class Testing */

/** EisiApi Class Testing */
$api = new EisiApi(
    env: EisiApiEnvironment::Testing,
    site: 3365,
    lang: EisiApiLang::Espanol,
    logType: LogType::FileLog
    // password: ''
);

// echo $api->getApiEnv();
// echo "\n";
// echo $api->getApiUrl();
// echo "\n";
// echo $api->getApiLang();
// echo "\n";
// echo $api->getApiSite();
// echo "\n";
// echo $api->getApiHashedPassword();
// echo "\n";
// echo $api->getApiIsReady();
// echo "\n";
// echo $api->getApiIsReadyHuman();
// echo "\n";

// $api->setApiSite(0);
// echo $api->getApiIsReadyHuman();
// echo "\n";

// $api->setApiSite(3365);
// echo $api->getApiIsReadyHuman();
// echo "\n";

// $api->setHashedPassword('');
// echo $api->getApiIsReadyHuman();
// echo "\n";

$api->setHashedPassword('P4dw0rdP4ss!!');
$api->log("Is the API ready to use? " . $api->getApiIsReadyHuman());

/**
 * 1. Listado de ubicaciones
 */
$response = $api->makeApiCall(endpoint: EisiApiEndpoint::LocationTree);
$api->log("RESPONSE :: " . EisiApiEndpoint::LocationTree->value . " :: " . json_encode($response));

/**
 * 2. Listado de problemas
 */
$response = $api->makeApiCall(
    endpoint: EisiApiEndpoint::ProblemsTree,
    params: [
        "departamento" => EisiApiDepartment::MANTENIMIENTO->value,
        "tip_arbol" => EisiApiTreeType::HOTEL->value
    ]
);

$api->log("PARAMS :: " . json_encode(
    [
        "departamento" => EisiApiDepartment::MANTENIMIENTO->value,
        "tip_arbol" => EisiApiTreeType::HOTEL->value
    ]
));

$api->log("RESPONSE :: " . EisiApiEndpoint::ProblemsTree->value . " :: " . json_encode($response));

/**
 * 3. Crear Task Body
 */
$body = $api->createTaskBody(
    service_tag_list: [],
    habitacion: '124',
    zona: 8451,
    problema: 25058,
    departamento: "L",
    observaciones: "Pruebas de integración FeelTourist",
    urls_fotos: [
        "https://thumbs.dreamstime.com/b/llame-por-tel%C3%A9fono-en-el-dormitorio-19981515.jpg",
        "https://thumbs.dreamstime.com/z/tel%C3%A9fono-en-la-habitaci%C3%B3n-33046804.jpg"
    ],
    remitida_cliente: 1,
    usuario: "FeelTourist",
);
$api->log("TASK-BODY :: " . json_encode($body));

/**
 * 4. Alta de Tarea
 */
// $response = $api->makeApiCall(endpoint: EisiApiEndpoint::CreateTask, body: $body);
// $api->log("RESPONSE: " . EisiApiEndpoint::ProblemsTree->value . " : " . json_encode($response));

/**
 * 5. Listado de departamentos
 */
$response = $api->makeApiCall(endpoint: EisiApiEndpoint::GetDepartmentsForSite);
$api->log("RESPONSE :: " . EisiApiEndpoint::GetDepartmentsForSite->value . " :: " . json_encode($response));

/** End EisiApi Class Testing */