# EISI API Class
## _EISI Hotel API Management Class_

<!-- [![PHP](https://camo.githubusercontent.com/7a890ba64a1aed3ec6f420ce76b2ad5c44310df9c3d1f15a7a3358304343649f/68747470733a2f2f7777772e7068702e6e65742f696d616765732f6c6f676f732f6e65772d7068702d6c6f676f2e737667)](https://github.com/cbenitez101/EisiApi) -->

<!-- [![Build Status](https://travis-ci.org/joemccann/dillinger.svg?branch=master)](https://github.com/cbenitez101/EisiApi) -->

EISI HOTEL API management class and enums.

<!-- - Type some Markdown on the left
- See HTML in the right
- ✨Magic ✨ -->

## Features

- EisiApi main class
- Enum for options values
- Logging class to manage API output - TextLog and FileLog
- @TODO: DatabaseLog

<!-- Markdown is a lightweight markup language based on the formatting conventions
that people naturally use in email.
As [John Gruber] writes on the [Markdown site][df1]

> The overriding design goal for Markdown's
> formatting syntax is to make it as readable
> as possible. The idea is that a
> Markdown-formatted document should be
> publishable as-is, as plain text, without
> looking like it's been marked up with tags
> or formatting instructions.

This text you see here is *actually- written in Markdown! To get a feel
for Markdown's syntax, type some text into the left window and
watch the results in the right. -->

## Classes

Main Classes:

- EisiApi
- Log
  - TextLog
  - FileLog
  - DatabaseLog


## Eisi Api Enums
Enums to correctly make requests to the API
 - EisiApiEnvironment: API environment to select which URL to use.
   - >Testing = 'Testing'
   - >Producion = 'Production'

 - EisiApiUrl: API URLs depending on which environment is being used.
   - > Testing = 'http://apitest.eisisoft.com'
   - > Production = 'https://api.eisisoft.com'

 - EisiApiLang: API requests languages
   - > Espanol = 'es'
   - > English = 'en'
   - > Catala = 'ca'
   - > Francoise = 'fr'
   - > Italiano = 'it'
   - > Portugues = 'pt'
   - > Deutsch = 'de'

 - EisiApiEndpoint: API Endpoints
   - > LocationTree = '/tasksapi/locationtree'
   - > ProblemsTree = '/tasksapi/problemstree'
   - > CreateTask = '/tasksapi/create_task'
   - > GetDepartmentsForSite = '/tasksapi/getDepartmentsForSite'
   - > GetHistory = '/tasksapi/get_history'

 - EisiApiMethod: Request Method
   - > GET = 'GET'
   - > POST = 'POST'

 - EisiApiDepartment: selectable departments
   - > MANTENIMIENTO = 'M'
   - > LIMPIEZA = 'L'
   - > IT = 'IT'
   - > GEX = 'GEX'
   - > SEGURIDAD = 'SEGURIDAD'

 - EisiApiTreeType: 
   - > HOTEL = 'H'
   - > OTRASZONAS = 'OZ'

## Log Enums
 - LogType: Log Types. @TODO: DatabaseLog
   - > TextLog = 'TextLog'
   - > FileLog = 'FileLog'
   - > DatabaseLog = 'DatabaseLog'

## Usage

Creating class instance
```sh
$api = new EisiApi(
    env: EisiApiEnvironment::Testing,
    site: 3365,
    lang: EisiApiLang::Espanol,
    logType: LogType::FileLog
);
```

Using Getters and Setters

```sh
// Get configured environment
$api->getApiEnv();

// Get configured URL
$api->getApiUrl();

// Get configured Lang
$api->getApiLang();

// Get configured Site
$api->getApiSite();

// Get configured Hash made from password and site combination
$api->getApiHashedPassword();

// Gets is API is ready to use (boolean)
$api->getApiIsReady();

// Gets is API is ready to use (human readable)
$api->getApiIsReadyHuman();

// Set Site
$api->setApiSite(3365);

// Sets Environment (which sets URL)
$api->setApiEnv(EisiApiEnvironment::Testing);

// Sets Language
$api->setApiLanguage(EisiApiLang::Espanol);

// Log an action made with the API using logType
$api->log("Message");

// Creates Hash and sets password
$api->setHashedPassword('password');

```

## Making API call
```sh
// Get Locations
$response = $api->makeApiCall(endpoint: EisiApiEndpoint::LocationTree);

// Get Problems Tree
$response = $api->makeApiCall(
    endpoint: EisiApiEndpoint::ProblemsTree,
    params: [
        "departamento" => EisiApiDepartment::MANTENIMIENTO->value,
        "tip_arbol" => EisiApiTreeType::HOTEL->value
    ]
);

// Format TaskBody to create Task
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

// Create Task
$response = $api->makeApiCall(endpoint: EisiApiEndpoint::CreateTask, body: $body);

// Get Departments of a site
$response = $api->makeApiCall(endpoint: EisiApiEndpoint::GetDepartmentsForSite);
```

## License

MIT

**Free Software, Hell Yeah!**

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen. Thanks SO - http://stackoverflow.com/questions/4823468/store-comments-in-markdown-syntax)

   [git-repo-url]: <https://github.com/cbenitez101/EisiApi.git>