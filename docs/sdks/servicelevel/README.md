# ServiceLevel nativo

Adaptador Source del contrato v1 de ServiceLevel, con modelos PHP tipados y el registro `$sdk->serviceLevel`. Esta entrega aislada no está publicada ni Applied; las capacidades de producción permanecen desactivadas y la matriz comercial sin ratificar.

| Método PHP / operationId | HTTP relativo al servidor `/v1` |
|---|---|
| `getServiceSlaStatus($ticket)` | `GET /crm/service-tickets/{ticket}/sla` |
| `getServiceSlaHistory($ticket, $page, $perPage)` | `GET /crm/service-tickets/{ticket}/sla/history` |
| `listServiceCalendars($page, $perPage)` | `GET /crm/service-calendars` |
| `configureServiceSla($ticket, $body, $originalKey)` | `PUT /crm/service-tickets/{ticket}/sla` |
| `pauseServiceSla($ticket, $body, $originalKey)` | `POST /crm/service-tickets/{ticket}/sla/pause` |
| `resumeServiceSla($ticket, $body, $originalKey)` | `POST /crm/service-tickets/{ticket}/sla/resume` |
| `updateServiceCalendar($body, $originalKey)` | `PUT /crm/service-calendars` |

Las lecturas usan los scopes nativos `service_level:read` y CRM; las escrituras, `service_level:write` y CRM. La empresa, actor, sandbox, credencial y permisos se resuelven mediante los productores Company y la autorización existente del servidor. El SDK conserva Bearer/API-key, `Factuarea-Version` y `X-Active-Profile` del SDK vigente. No añade OAuth, grants ni campos de autoridad al cuerpo.

`id`, `subject_id`, `cycle_id`, `policy_id`, `calendar_id`, `previous_cycle_id` y `message_id` conservan sus significados públicos y sus UUIDv7 nativos. Crear un calendario declara `id: null` y `expected_version: null`; crear un ciclo/política conserva las reservas `cycle_id: null` y `policy_id: null`. Revisar mantiene la identidad real y envía la versión CAS original. Los campos nullable se serializan explícitamente. No se asignan identificadores ni se calcula una versión de éxito en el cliente.

Las respuestas siguen el patrón SDK `$response->object->data`, con `statusCode`, `contentType`, `headers` y `rawResponse`. El resultado de escritura es exclusivamente el recibo nativo `{id, version}`. Los listados SLA contienen `items`, `total`, `page` y `per_page` dentro de `data`; usan páginas entre 1 y 1000000 y tamaños entre 1 y 100. El paginador cursor existente no corresponde a este contrato. Los instantes conservan sus strings ISO y precisión; las duraciones se representan con enteros de microsegundos.

Cada llamada envía una sola solicitud y desactiva redirects. Las cuatro escrituras exigen una clave original ASCII de 1–255 caracteres y omiten el mecanismo automático de retry y los hooks que reemplazan errores por éxito. Un transporte personalizado también debe conservar esta propiedad: el SDK no controla middleware de retry instalado por el integrador.

Un timeout, un 5xx, el `409` nativo `service_level_result_unconfirmed` o un HTTP 200 sin recibo válido lanza `ServiceLevelWriteUnconfirmed`. Su `intent` conserva una copia inmutable del JSON inicial, identidad, CAS, clave y headers efectivos de versión/perfil. `cause` conserva el error tipado nativo cuando está disponible. La recuperación es una decisión explícita del consumidor:

```php
use Factuarea\Sdk\Models\Errors\ServiceLevelWriteUnconfirmed;

try {
    $response = $sdk->serviceLevel->updateServiceCalendar($calendarRequest, $originalKey);
} catch (ServiceLevelWriteUnconfirmed $error) {
    // Conservar $error->intent. No construir otro cuerpo, identidad, CAS o clave.
    $originalIntent = $error->intent;
}

// Sólo cuando el consumidor decida consultar el resultado original:
if (isset($originalIntent)) {
    $response = $sdk->serviceLevel->recover($originalIntent);
}
```

`recover` realiza una única solicitud al mismo operationId y ruta, con los valores iniciales. Usa la recuperación por recibo del backend: una reserva existente no vuelve a despachar el comando. Servidor/credencial distintos se rechazan antes de enviar; versión y perfil efectivos permanecen congelados. Un resultado todavía ambiguo vuelve a lanzar `ServiceLevelWriteUnconfirmed`; no se crea un bucle automático. Un `409` CAS, `idempotency_key_reused`, `401/403/404/422/429` conserva `Models\Errors\ErrorThrowable` y su envelope, código, subcódigo, parámetro, request ID y respuesta original. No se transforma un rechazo en confirmación.

Las lecturas no producen hechos ni acreditan respuestas públicas. `queued` no satisface relojes, y `waiting_customer` depende del productor canónico; calendarios SLA no alteran horarios RRHH ni plazos Task.

Las pruebas de `Tests/Custom/ServiceLevel/ServiceLevelContractTest.php` están escritas para rutas, reservas, serialización, recibos, paginación, errores, timeout y recuperación. Su ejecución HTTP/sandbox, la generación desde spec publicado, una versión/release compatible y la entrega pública coordinada siguen pendientes. No se atribuye ejecución de pruebas a comprobaciones estáticas.
