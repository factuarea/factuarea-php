# Evidencia de release CRM PHP — pendiente

Captura local del 2026-10-13 para `crm-php-sdk-delivery` 3.1 (CRM-083).
Este documento identifica Source y prepara la receta de verificación; no
certifica una release, instalación nueva ni compatibilidad runtime ejecutada.

| Identidad observada | Valor |
| --- | --- |
| Repositorio Source | `repos/factuarea-php`, paquete Composer `factuarea/factuarea-php` |
| Rama | `feature/dev-2-crm-php-sdk-source-20261009` |
| HEAD local | `68efc9187a71f98580cdfde33f077d095fd64aca` |
| Estado | Cambios locales y archivos sin seguimiento; HEAD no identifica todos los bytes actuales |
| Namespace / mínimo declarado | `Factuarea\Sdk`, PHP `>=8.2` |
| Versión | `composer.json` no fija versión de paquete; la configuración Speakeasy declara SDK `0.5.0`. No se asigna versión de release ni instalada |
| OpenAPI CRM Source | `spec/crm-openapi.json`, 77 operationIds con `x-crm-operation` |
| SHA-256 OpenAPI CRM | `4a2cb151faef526aa8c8fd26c29a1d3f6acd019335e13b058b86671f40eb609f` |
| SHA-256 efectos | `spec/crm-effects.json`: `fe8fc780e346f366b9376496f4da595cd197ba4ad0eea8c7b5515bc870f35f19` |
| SHA-256 inventario local | `spec/crm-operations.json`: `4f1d5a2268e3cc20412260e3197931121772ba3ae70ab3298c6fa88e823bb98b` |

Los 77 contratos son un fragmento congelado de ContactPeople, Lead, Pipeline,
Knowledge y PublicHelpCenter. No se atribuyen a todo el inventario CRM actual
ni a una API publicada. Los handlers nativos clasifican efectos; el verbo POST
no convierte una Query en mutación. Auth, UUID, cursor, omitted/null, decimal
string, enums futuros de respuesta y errores tipados tienen artefactos fuente
en `Tests/Custom/Crm/`, incluida la matriz HTTP por operationId. Sus casos nuevos
siguen pendientes de ejecución en este encargo.

La configuración de generación conciliada para 2.1 pertenece al worktree
`development/ecosystem105/php-sdk`, rama
`feature/dev-2-php-sdk-development105`, HEAD
`d04af93359db951ab2627e7eea21406e443dcdbe`. Su
`spec/crm-generator.json` tiene SHA-256
`ea780ca335e9d42d8d53ccf59cfcddf6d2ed36df3d8ecf485ca0009f2e5fc2f8`.
Comparte el OpenAPI CRM anterior; su inventario tiene SHA-256
`6fa54bdc98cc34de28deff50db0732289aa185e035cc19f2367d6d62c31c5f15`.
Se mantienen separados: no se declara un merge ni una regeneración realizada.

Las recetas comerciales ya implementadas están en
`development/factuarea-php-servicelevel/examples/CrmWebsite.php` y
`examples/CrmJourney.php`. La evidencia histórica de instalación privada del
change está en
`app/openspec/changes/crm-php-sdk-delivery/external-capability-evidence.md`.
Esa evidencia usa una copia local concreta y HTTP fake; no certifica que estos
bytes actuales estén instalados ni una versión publicada. `examples/crm.php`
de este fragmento usa el autoload y los modelos reales para Lead; no finge
disponer aquí del journey completo.

## Receta pendiente del operador

1. Conciliar los worktrees propietarios y el export nativo vigente sin perder
   los contratos anteriores. Registrar el commit que contenga todos los archivos
   y el hash del OpenAPI exacto de ese commit; el HEAD anterior no lo sustituye.
2. Preparar un consumidor Composer privado con repositorio `path` y
   `symlink: false`, fijado a ese Source. Conservar el lock y hashes de la copia
   instalada; usar su autoload authoritative y sus ejemplos, sin fallback al
   checkout. Una versión local de verificación debe identificarse como tal.
3. Comprobar plataforma y autoload en PHP 8.2 y en las versiones soportadas;
   ejecutar los contratos HTTP fake y recetas desde la copia instalada en la
   fase de validación autorizada. Guardar comandos, versiones y resultados
   reales, incluidos fallos, sin secretos ni datos de clientes.
4. Verificar contra sandbox nativo la credencial actual, scopes, tenant A/B,
   rol/plan, masks, revocación y recuperación original aplicables. Las pruebas
   fake y la metadata Composer no demuestran esos permisos del servidor.
5. La publicación coordinada necesita autorización de release y su artefacto
   identificable. Conservar version/tag/commit, hash del archivo entregado y
   prueba de instalación por un consumidor externo; ningún paso se ha ejecutado
   en esta captura.

3.1 permanece pendiente. No se ejecutaron suites, DB, generación, Composer
install, publicación, push, PR ni activación; no se modifica runtime-evidence.
