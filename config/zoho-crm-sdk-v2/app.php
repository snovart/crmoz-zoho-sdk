<?php
return [
    'hooks_prefix' => '',
    'redis_prefix' => env('ZOHO_SDK_REDIS_PREFIX','project'),
    'crm_token' => env('ZOHO_CRM_TOKEN',''),
    'crm_verify_token' => env('ZOHO_CRM_VERIFY_TOKEN',''),
    'constraints_api' => [
        'insert' => 100,
        'update' => 100,
        'get' => 200,
        'search' => 200,
        'delete' => 100
    ],
    'sync_records_interval' => 30,
    'sync_records_sub_type' => 'Minutes',
    /**
     * Модели исключенные из массовой синхронизации (имена классов)
     */
    'sync_full_excluded' => [],
    'sync_refresh_excluded' => [],
    'sync_excluded' => [],
    /**
     * Количество попыток запросов при превышении количества запросов API(в минуту, в день)
     * Если все попытки не удались, выдаст TryOutException
     */
    'limit_try_requests' => 3,
    'api_hosts' => [
        'com' => 'https://www.zohoapis.com/crm/v2/',
        'eu' => 'https://www.zohoapis.eu/crm/v2/',
        'in' => 'https://www.zohoapis.in/crm/v2/'
    ],
    'current_host' => 'eu',
    'command_after_hook' => null,

    /**
     * sync - синхронное выполнение напярмую в контроллере
     * async - асинхронное выполнение через очереди
     * Для асинхронного нужна работа с  Redis, Supervizor,
     * создать таблицу для проваленых задач
     * php artisan queue:failed-table
     * php artisan migrate
     */
    'mode_hook' => 'sync',

    /**
     * Логирование запросов к СРМ АПИ в БД
     * Нужна миграция для таблици zoho_sdk_requests_log
     * php artisan migrate
     */
    'requests_log' => env('ZOHO_CRM_SDK_REQUESTS_LOG',false),
    'response_log' => env('ZOHO_CRM_SDK_RESPONSE_LOG',false)

];
