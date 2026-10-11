#!/usr/bin/env python3
"""Generate additive CRM DTOs/methods from the frozen native OpenAPI fragment.

Does not touch any Speakeasy-managed file, infer operations or fetch a live spec.
"""
import argparse
import hashlib
import json
import os
from pathlib import Path
import re
import sys

os.umask(0o022)
ROOT = Path(__file__).resolve().parent.parent
arguments = argparse.ArgumentParser(description=__doc__)
arguments.add_argument('--private-source139', metavar='NATIVE_INPUT_MANIFEST', help='Generate a separate unreleased namespace from the literal native export.')
options = arguments.parse_args()
PRIVATE = options.private_source139 is not None
SPEC = ROOT / ('spec/crm-native139-source.json' if PRIVATE else 'spec/crm-openapi.json')
if PRIVATE:
    metadata = json.loads(Path(options.private_source139).read_text())
    artifact = Path(metadata['artifact'])
    raw = (artifact if artifact.is_file() else SPEC).read_bytes()
    if hashlib.sha256(raw).hexdigest() != metadata['artifact_sha256']:
        raise ValueError('The literal native export does not match its retained input hash.')
    native = json.loads(raw)
    profile = native.get('x-factuarea-contract-profile', {})
    if (metadata.get('published') is not False or metadata.get('ratified') is not False
            or metadata.get('delivery_enabled') is not False or metadata.get('capabilities') != []
            or profile.get('name') != 'crm-source-contracts' or profile.get('published') is not False
            or profile.get('ratified') is not False or profile.get('delivery_enabled') is not False):
        raise ValueError('This mode accepts only the explicit private, unreleased native Source profile.')
    SPEC.write_bytes(raw)
    metadata['source_profile'] = {key: profile[key] for key in ['name', 'source', 'state', 'published', 'ratified', 'delivery_enabled']}
document = json.loads(SPEC.read_text())
schemas = document['components']['schemas']
effects = json.loads((ROOT / 'spec/crm-effects.json').read_text())['operations']
NAMESPACE = 'Factuarea\\Sdk\\Custom\\Crm\\' + ('Native139\\' if PRIVATE else '') + 'Model\\'
API_NAMESPACE = 'Factuarea\\Sdk\\Custom\\Crm' + ('\\Native139' if PRIVATE else '')
OUTPUT = ROOT / 'src/Custom/Crm' / ('Native139' if PRIVATE else '')
(OUTPUT / 'Model').mkdir(parents=True, exist_ok=True)
models = {}
model_schemas = {}
rendering = set()

def pascal(value):
    return ''.join(piece[:1].upper() + piece[1:] for piece in re.split(r'[^A-Za-z0-9]', value) if piece)

def camel(value):
    result = pascal(value)
    return result[:1].lower() + result[1:]

def php(value):
    if value is None:
        return 'null'
    if isinstance(value, bool):
        return 'true' if value else 'false'
    if isinstance(value, (int, float)):
        return str(value)
    if isinstance(value, str):
        return "'" + value.replace('\\', '\\\\').replace("'", "\\'") + "'"
    if isinstance(value, list):
        return '[' + ', '.join(php(x) for x in value) + ']'
    if isinstance(value, dict):
        return '[' + ', '.join(php(k) + ' => ' + php(v) for k, v in value.items()) + ']'
    raise TypeError(type(value))

def join_types(types):
    values = list(dict.fromkeys(types))
    values = list(dict.fromkeys(part for value in values for part in value.split('|')))
    return 'mixed' if 'mixed' in values else '|'.join(values)

def describe(schema, name):
    if '$ref' in schema:
        ref = schema['$ref'].split('/')[-1]
        return describe(schemas[ref], pascal(ref))
    if 'allOf' in schema and 'type' not in schema:
        # Native receipt snapshots narrow a referenced DTO with extra constraints.
        # Keep its real type; the enclosing SCHEMA validates every conjunct.
        bases = [branch for branch in schema['allOf'] if '$ref' in branch or 'type' in branch]
        if PRIVATE and len(bases) > 1:
            render_model(name, schema)
            return ('\\' + NAMESPACE + name, '\\' + NAMESPACE + name, {'model': NAMESPACE + name})
        if len(bases) != 1:
            raise ValueError('An allOf field needs one native typed base: ' + name)
        return describe(bases[0], name)
    if isinstance(schema.get('type'), list) and len(schema['type']) > 1:
        choices = [describe(dict(schema, type=variant), name) for variant in schema['type']]
        return (join_types([x[0] for x in choices]), join_types([x[1] for x in choices]), {'union': [x[2] for x in choices]})
    schema_types = schema.get('type', [])
    nullable = isinstance(schema_types, list) and 'null' in schema_types
    schema_type = next((x for x in schema_types if x != 'null'), None) if isinstance(schema_types, list) else schema_types
    if schema_type == 'object' and (schema.get('properties') or schema.get('additionalProperties') is False):
        render_model(name, schema)
        result = ('\\' + NAMESPACE + name, '\\' + NAMESPACE + name, {'model': NAMESPACE + name})
    elif 'anyOf' in schema or 'oneOf' in schema:
        variants = schema.get('anyOf', schema.get('oneOf'))
        choices = [describe(s, name + 'Variant' + str(i + 1)) for i, s in enumerate(variants)]
        result = (join_types([x[0] for x in choices]), join_types([x[1] for x in choices]), {'union': [x[2] for x in choices]})
    elif schema_type == 'array':
        item = describe(schema.get('items', {}), name + 'Item')
        result = ('array', 'list<' + item[1] + '>', {'items': item[2]})
    elif schema_type == 'object':
        result = ('\\stdClass', '\\stdClass', {'object': True})
    else:
        scalar = {'string': 'string', 'integer': 'int', 'number': 'float', 'boolean': 'bool', 'null': 'null'}.get(schema_type, 'mixed')
        result = ('int|float' if scalar == 'float' else scalar, 'int|float' if scalar == 'float' else scalar, {'scalar': scalar})
    if nullable and 'null' not in result[0].split('|') and result[0] != 'mixed':
        return (result[0] + '|null', result[1] + '|null', {'union': [result[2], {'scalar': 'null'}]})
    return result

HEADER = '''<?php

/** Generated by tools/generate-crm.py from spec/crm-openapi.json. */

declare(strict_types=1);

namespace Factuarea\\Sdk\\Custom\\Crm\\Model;

use Factuarea\\Sdk\\Custom\\Crm\\Omitted;
use Factuarea\\Sdk\\Custom\\Crm\\Schema;
use Factuarea\\Sdk\\Custom\\Crm\\WireModel;

'''

def object_fields(schema):
    schema = local_reference(schema)
    props, required = {}, []
    for branch in schema.get('allOf', []):
        child_props, child_required = object_fields(branch)
        props.update(child_props)
        required += child_required
    props.update(schema.get('properties') or {})
    required += schema.get('required', [])
    return props, list(dict.fromkeys(required))

def render_model(name, schema):
    if name in models or name in rendering:
        return
    rendering.add(name)
    props, required = object_fields(schema) if PRIVATE else (schema.get('properties') or {}, schema.get('required', []))
    field_data = []
    for wire, field in props.items():
        type_hint, doc_type, desc = describe(field, name + pascal(wire))
        optional = wire not in required
        if optional:
            type_hint = join_types(type_hint.split('|') + ['Omitted'])
            doc_type = join_types(doc_type.split('|') + ['Omitted'])
        field_data.append((wire, camel(wire), type_hint, doc_type, desc, optional))
    field_data.sort(key=lambda x: x[5])
    fields = {wire: {'property': attr, 'type': desc} for wire, attr, _, _, desc, _ in field_data}
    header = HEADER.replace('namespace Factuarea\\Sdk\\Custom\\Crm\\Model;', 'namespace ' + NAMESPACE.rstrip('\\') + ';') if PRIVATE else HEADER
    header = header.replace('from spec/crm-openapi.json.', 'from the private native spec/crm-native139-source.json; unreleased.') if PRIVATE else header
    lines = [header, 'final class ' + name + ' extends WireModel\n{\n',
             ("    public const SCHEMA_DOCUMENT = 'crm-native139-schemas.json';\n\n" if PRIVATE else ''),
             '    public const FIELDS = ' + php(fields) + ';\n\n    public const SCHEMA = ' + php(schema) + ';\n\n']
    lines.append('    /**\n')
    for _, attr, _, doc_type, _, _ in field_data:
        lines.append('     * @param ' + doc_type + ' $' + attr + '\n')
    lines.append('     */\n    public function __construct(\n')
    for _, attr, type_hint, _, _, optional in field_data:
        lines.append('        public ' + type_hint + ' $' + attr + (' = Omitted::Value' if optional else '') + ',\n')
    lines.append('    ) {\n        Schema::validate(self::SCHEMA, $this->jsonSerialize()' + (", '$', self::SCHEMA_DOCUMENT" if PRIVATE else '') + ');\n    }\n}\n')
    models[name] = ''.join(lines)
    model_schemas[name] = schema
    rendering.remove(name)

def local_reference(value):
    if '$ref' not in value:
        return value
    reference = value['$ref']
    if not reference.startswith('#/'):
        raise ValueError('Only local frozen OpenAPI references are supported.')
    target = document
    for token in reference[2:].split('/'):
        target = target[token.replace('~1', '/').replace('~0', '~')]
    return local_reference(target)

def resolved(schema):
    return schemas[schema['$ref'].split('/')[-1]] if '$ref' in schema else schema

def page_shape(schema, prefix=None):
    prefix = prefix or []
    schema = resolved(schema)
    props = schema.get('properties', {})
    if 'has_more' in props and 'next_cursor' in props:
        for key in ['data', 'items', 'results']:
            field = resolved(props.get(key, {}))
            if field.get('type') == 'array':
                return prefix + [key], field.get('items', {})
    for key in ['data']:
        if key in props:
            shape = page_shape(props[key], prefix + [key])
            if shape:
                return shape
    return None

apis = {'ContactPeople' : [], 'Leads': [], 'Pipelines': [], 'KnowledgeBase': [], 'PublicHelpCenter': []}
if PRIVATE:
    apis.update({'Opportunities': [], 'Activities': [], 'SpreadsheetImports': [], 'Documents': [], 'TaskAgenda': [], 'EntityExports': []})
operation_map = []
for path, item in document['paths'].items():
    for method, operation in item.items():
        if method not in ['get', 'post', 'put', 'patch', 'delete']:
            continue
        key = operation.get('x-crm-operation', '')
        if key.startswith('crm_contact_people.'):
            group = 'ContactPeople'
        elif key.startswith('crm_leads.'):
            group = 'Leads'
        elif key.startswith('crm_pipelines.'):
            group = 'Pipelines'
        elif key.startswith('knowledge_articles.'):
            group = 'KnowledgeBase'
        elif key.startswith('public_help_centers.'):
            group = 'PublicHelpCenter'
        elif PRIVATE and operation['operationId'] in ['crmCreateOpportunity', 'crmGetOpportunityCreateOriginal', 'crmGetOpportunity', 'crmListOpportunities']:
            group = 'Opportunities'
        elif PRIVATE and operation['operationId'] in ['crmCreateCrmActivity', 'crmGetCrmActivityCreateOriginal', 'crmGetCrmActivity', 'crmListCrmActivities']:
            group = 'Activities'
        elif PRIVATE and operation['operationId'] in ['public-api.v1.crm-spreadsheet-imports.apply-original', 'public-api.v1.crm-spreadsheet-imports.show']:
            group = 'SpreadsheetImports'
        elif PRIVATE and key in ['crm_documents.get', 'crm_documents.list']:
            group = 'Documents'
        elif PRIVATE and operation['operationId'] == 'public-api.v1.agenda.list' and method == 'get':
            group = 'TaskAgenda'
        elif PRIVATE and key in ['crm_imports.export', 'crm_imports.export_original', 'crm_imports.export_download']:
            group = 'EntityExports'
        elif PRIVATE:
            continue
        else:
            raise ValueError('An operation outside the approved CRM owners was exported: ' + key)
        original_read = PRIVATE and operation.get('x-crm-original-read') is True
        effect = False if original_read else effects[key]['effect'] if not PRIVATE or key in effects else method != 'get'
        if not isinstance(effect, bool):
            raise ValueError('The native handler effect classification is missing.')
        op_id = operation['operationId']
        method_name = op_id if re.fullmatch(r'[A-Za-z_][A-Za-z0-9_]*', op_id) else camel(op_id)
        class_name = pascal(op_id)
        request_props, request_required = {}, []
        parameters = [local_reference(p) for p in operation.get('parameters', [])]
        for parameter in parameters:
            if parameter['in'] not in ['query', 'path', 'header'] or parameter['name'].lower() == 'idempotency-key':
                continue
            request_props[parameter['name']] = parameter.get('schema', {})
            if parameter.get('required'):
                request_required.append(parameter['name'])
        body = operation.get('requestBody', {}).get('content', {}).get('application/json', {}).get('schema')
        if body:
            request_props['body'] = body
            request_required.append('body')
        original_key = next((p for p in parameters if p['in'] == 'header' and p['name'].lower() == 'idempotency-key'), None)
        has_key = method != 'get' or original_key is not None
        if has_key:
            request_props['idempotency_key'] = original_key['schema'] if original_key else {'type': 'string', 'minLength': 1, 'maxLength': 255, 'pattern': '^[ -~]+$'}
            request_required.append('idempotency_key')
        request_schema = {'type': 'object', 'additionalProperties': False, 'properties': request_props, 'required': request_required}
        request_name = class_name + 'Parameters'
        render_model(request_name, request_schema)
        successes = [r['content']['application/json']['schema'] for code, r in operation['responses'].items() if str(code).startswith('2') and 'application/json' in r.get('content', {})]
        if PRIVATE and not successes:
            # The native export download is an existing Query-only CSV transport, not a JSON envelope.
            if operation['operationId'] != 'public-api.v1.crm-entity-exports.download':
                raise ValueError('The private native operation has no supported success representation: ' + op_id)
            lines = ['    public function ' + method_name + '(Model\\' + request_name + ' $request): \\Psr\\Http\\Message\\ResponseInterface\n    {\n',
                     '        $request->validate();\n',
                     "        return $this->transport->requestStream('crm/entity-exports/'.rawurlencode($request->export).'/download', " + php(op_id) + ');\n    }\n']
            apis[group].append(''.join(lines))
            operation_map.append({'operationId': op_id, 'method': method.upper(), 'path': path, 'group': group, 'request': request_name, 'response': 'PSR-7 native CSV stream', 'scope': operation.get('x-required-scope'), 'oauthScope': operation.get('x-required-oauth-scope'), 'key': key, 'effect': False})
            continue
        response_schema = successes[0]
        if any(s != response_schema for s in successes):
            raise ValueError('Success statuses require different envelopes: ' + op_id)
        if '$ref' in response_schema:
            response_schema = schemas[response_schema['$ref'].split('/')[-1]]
        if response_schema.get('type') != 'object' and not (PRIVATE and 'allOf' in response_schema):
            raise ValueError('Unsupported success envelope: ' + op_id)
        response_name = class_name + 'Result'
        render_model(response_name, response_schema)
        path_expr = php(path.lstrip('/'))
        for parameter in parameters:
            if parameter['in'] == 'path':
                token = '{' + parameter['name'] + '}'
                path_expr = 'str_replace(' + php(token) + ', rawurlencode($request->' + camel(parameter['name']) + '), ' + path_expr + ')'
        query = {p['name']: p for p in parameters if p['in'] == 'query'}
        lines = ['    /** @return CrmResponse<Model\\' + response_name + '> */\n',
                 '    public function ' + method_name + '(Model\\' + request_name + ' $request): CrmResponse\n    {\n',
                 '        $request->validate();\n        $query = [];\n']
        for wire, parameter_schema in query.items():
            attr = camel(wire)
            value = '$request->' + attr
            if parameter_schema.get('schema', {}).get('type') == 'boolean':
                value = "($request->" + attr + " ? 'true' : 'false')"
            statement = '$query[' + php(wire) + '] = ' + value + ';\n'
            if wire in request_required:
                lines.append('        ' + statement)
            else:
                lines += ['        if ($request->' + attr + ' !== Omitted::Value) {\n',
                          '            ' + statement, '        }\n']
        lines.append('        $headers = [];\n')
        for parameter in parameters:
            if parameter['in'] != 'header' or parameter['name'].lower() == 'idempotency-key':
                continue
            attr = camel(parameter['name'])
            if parameter['name'] in request_required:
                lines.append('        $headers[' + php(parameter['name']) + '] = $request->' + attr + ';\n')
            else:
                lines += ['        if ($request->' + attr + ' !== Omitted::Value) {\n',
                          '            $headers[' + php(parameter['name']) + '] = $request->' + attr + ';\n', '        }\n']
        success_statuses = [int(code) for code, response in operation['responses'].items() if str(code).startswith('2') and 'application/json' in response.get('content', {})]
        retained_hash = "hash('sha256', '')" if original_read else ("hash('sha256', $request->body->toJson())" if PRIVATE and method != 'get' and body else 'null')
        status_argument = ', ' + retained_hash + ', ' + php(success_statuses) if PRIVATE else ''
        lines.append('        ' + ('$response = ' if original_read else 'return ') + '$this->transport->request(' + php(method.upper()) + ', ' + path_expr + ', ' + php(op_id) + ', Model\\' + response_name + '::class, $query, ' + ('$request->body' if body else 'null') + ', ' + ('$request->idempotencyKey' if has_key else 'null') + ', ' + php(effect) + ', $headers' + status_argument + ');\n')
        if original_read:
            lines.append("        if ($response->body->data->originalBodySha256 !== $request->originalBodySha256) {\n            throw new \\UnexpectedValueException('The native Original must identify the retained request hash.');\n        }\n        return $response;\n")
        lines.append('    }\n')
        shape = page_shape(response_schema) if method == 'get' and 'cursor' in query else None
        mode = 'cursor'
        if shape is None and method == 'get' and 'page' in query:
            root_props = response_schema.get('properties', {})
            if root_props.get('data', {}).get('type') == 'array' and 'meta' in root_props:
                shape = (['data'], root_props['data'].get('items', {}))
                mode = 'page'
            elif 'data' in root_props:
                data_props = resolved(root_props['data']).get('properties', {})
                if all(k in data_props for k in ['items', 'total', 'page', 'per_page']):
                    shape = (['data', 'items'], data_props['items'].get('items', {}))
                    mode = 'numbered'
        if shape:
            items_path, item_schema = shape
            _, item_doc, item_desc = describe(item_schema, class_name + 'ListItem')
            parameter = 'cursor' if mode == 'cursor' else 'page'
            assign = '$cursor' if mode == 'cursor' else '(int) $cursor'
            lines += ['\n    /** @return \\Generator<int, ' + item_doc + '> */\n',
                      '    public function ' + method_name + 'Items(Model\\' + request_name + ' $request, int $maxPages = 1000): \\Generator\n    {\n',
                      '        return Pagination::items(function (?string $cursor) use ($request): CrmResponse {\n',
                      '            $page = clone $request;\n',
                      '            if ($cursor !== null) {\n                $page->' + parameter + ' = ' + assign + ';\n            }\n',
                      '            return $this->' + method_name + '($page);\n',
                      '        }, ' + php(items_path) + ', ' + php(item_desc) + ', ' + php(mode) + ', $maxPages);\n    }\n']
        apis[group].append(''.join(lines))
        operation_map.append({'operationId': op_id, 'method': method.upper(), 'path': path, 'group': group, 'request': request_name, 'response': response_name, 'scope': operation.get('x-required-scope'), 'key': key, 'effect': effect})
        if PRIVATE:
            operation_map[-1]['oauthScope'] = operation.get('x-required-oauth-scope')
        if method_name != op_id:
            operation_map[-1]['phpMethod'] = method_name

if not PRIVATE:
    for schema_name, schema in sorted(schemas.items()):
        if schema_name != 'Error':
            describe(schema, pascal(schema_name))

if PRIVATE:
    # Retain native definitions unchanged, following only references reached by these DTOs.
    # This derived cache avoids loading all unrelated operations into PHP validation memory.
    retained = {}

    def retain_references(value):
        if isinstance(value, dict):
            reference = value.get('$ref')
            if reference is not None:
                prefix = '#/components/schemas/'
                if not reference.startswith(prefix):
                    raise ValueError('A native DTO reference must resolve within components.schemas.')
                name = reference[len(prefix):].replace('~1', '/').replace('~0', '~')
                if name not in retained:
                    retained[name] = schemas[name]
                    retain_references(schemas[name])
            for child in value.values():
                retain_references(child)
        elif isinstance(value, list):
            for child in value:
                retain_references(child)

    for schema in model_schemas.values():
        retain_references(schema)
    cache = {'x-native-source-sha256': metadata['artifact_sha256'], 'components': {'schemas': dict(sorted(retained.items()))}}
    cache_bytes = (json.dumps(cache, indent=2, ensure_ascii=False) + '\n').encode()
    (ROOT / 'spec/crm-native139-schemas.json').write_bytes(cache_bytes)
    metadata['schema_cache_sha256'] = hashlib.sha256(cache_bytes).hexdigest()
    (ROOT / 'spec/crm-native139-provenance.json').write_text(json.dumps(metadata, indent=2) + '\n')

for name, source in sorted(models.items()):
    (OUTPUT / 'Model' / (name + '.php')).write_text(source)
for name, methods in apis.items():
    source = '''<?php

/** Generated by tools/generate-crm.py from spec/crm-openapi.json. */

declare(strict_types=1);

namespace Factuarea\\Sdk\\Custom\\Crm;

final readonly class ''' + name + '''
{
    public function __construct(private Transport $transport) {}

''' + '\n'.join(methods) + '}\n'
    if PRIVATE:
        source = source.replace('namespace Factuarea\\Sdk\\Custom\\Crm;', 'namespace ' + API_NAMESPACE + ';\n\nuse Factuarea\\Sdk\\Custom\\Crm\\{CrmResponse, Omitted, Pagination, Transport};')
        source = source.replace('from spec/crm-openapi.json.', 'from the private native spec/crm-native139-source.json; unreleased.')
    (OUTPUT / (name + '.php')).write_text(source)
(ROOT / ('spec/crm-native139-operations.json' if PRIVATE else 'spec/crm-operations.json')).write_text(json.dumps(operation_map, indent=2) + '\n')
print(json.dumps({'operations': len(operation_map), 'models': len(models), 'spec_sha256': hashlib.sha256(SPEC.read_bytes()).hexdigest()}))
