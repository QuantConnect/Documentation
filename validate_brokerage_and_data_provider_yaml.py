# This script scans the YAML file to find all the supported brokerages and data providers.
# It then tries to deploy an algorithm with each one.
# Deployments that omit some `required` properties are tested to ensure they fail.
# Deployments will all `required` properties are tested to ensure they return {'success': True}.
from base64 import b64encode
from hashlib import sha256
from time import time, sleep
import requests
import os

# Inputs:
USER_ID = os.getenv('QUANTCONNECT_USER_ID')
API_TOKEN = os.getenv('QUANTCONNECT_API_TOKEN')
raise Exception(f"{USER_ID} -- {API_TOKEN}")
BASE_URL = 'https://www.quantconnect.com/api/v2/'
YAML_URL = 'https://raw.githubusercontent.com/QuantConnect/Documentation/refs/heads/master/QuantConnect-Platform-2.0.0.yaml'
DEFAULT_BROKERAGE = {
    'id': 'QuantConnectBrokerage'
}
DEFAULT_DATA_PROVIDERS = {
    'QuantConnectBrokerage': {
        'id': 'QuantConnectBrokerage'
    }
}
DEFAULT_VALUE_BY_TYPE = {
    'string': ' ',
    'integer': 1,
    'number': 1.0,
    'boolean': True,
    'array': []
}
VALUE_OVERRIDE_BY_PROPERTY = {
    # For TT brokerage, cash is required and passing an empty 
    # list throws an error.
    'cash': [{'amount': 100_000, 'currency': 'USD'}], 

    'ib-weekly-restart-utc-time': '12:00:00',
}

def get_headers():
    # Get timestamp
    timestamp = f'{int(time())}'
    time_stamped_token = f'{API_TOKEN}:{timestamp}'.encode('utf-8')
    # Get hased API token
    hashed_token = sha256(time_stamped_token).hexdigest()
    authentication = f'{USER_ID}:{hashed_token}'.encode('utf-8')
    authentication = b64encode(authentication).decode('ascii')
    # Create headers dictionary.
    return {
        'Authorization': f'Basic {authentication}',
        'Timestamp': timestamp
    }

def post(endpoint, payload):
    if endpoint == '/live/create':
        print(payload)
    return requests.post(
        f'{BASE_URL}{endpoint}', headers=get_headers(), json=payload
    ).json()

def prepare_live_payload():
    # Create a project.
    project_id = post(
        '/projects/create', {'name': 'TEST Project', 'language': 'Py'}
    )['projects'][0]['projectId']
    # Compile it.
    compile_id = post(
        '/compile/create', {'projectId': project_id}
    )['compileId']
    wait_for_compile_to_complete(project_id, compile_id)
    # Get a node Id.
    nodes = post(
        '/projects/nodes/read', {'projectId': project_id}
    )['nodes']['live']
    node_id = [n for n in nodes if not n['hasGpu'] and not n['busy']][-1]['id']
    return project_id, compile_id, node_id

def wait_for_compile_to_complete(project_id, compile_id):
    attempts = 0
    while attempts < 10:
        attempts += 1
        response = post(
            '/compile/read', {'projectId': project_id, 'compileId': compile_id}
        )
        if response['state'] != 'InQueue':
            return # Done.
        sleep(2)
    assert False, "Compile job stuck in queue."

def create_live_algorithm(
        project_id, compile_id, node_id, brokerage, data_providers):
    return post(
        '/live/create', 
        {
            'projectId': project_id,
            'compileId': compile_id,
            'nodeId': node_id, 
            'versionId': '-1',
            'brokerage': brokerage,
            'dataProviders': data_providers
        }
    )


class Property:

    def __init__(self, name):
        self.name = name
        self.enums = []

    def set_type(self, type_):
        self.type = type_

    def add_enum(self, enum):
        self.enums.append(enum)


# Define a class for each brokerage setting.
class Brokerage:

    def __init__(self, schema_name):
        self.schema_name = schema_name
        self.properties = []
        self.required_properties = []

    def add_property(self, name):
        self.properties.append(Property(name))

    def set_property_type(self, type_):
        self.properties[-1].set_type(type_)

    def make_property_required(self, property_name):
        self.required_properties.append(property_name)

    def add_property_enum(self, enum):
        self.properties[-1].add_enum(enum)


class DataProvider(Brokerage):

    def __init__(self, key, schema_name):
        self.key = key
        self.schema_name = schema_name
        self.properties = []
        self.required_properties = []


def indents(line):
    return len(line) - len(line.lstrip())

# Define a method to get all the brokerage and data providers the 
# `/live/create` endpoint supports.
def get_supported_models(yaml):
    # Get the list of brokerages and data providers that the 
    # CreateLiveAlgorithmRequest supports.
    parsing_request = False
    parsing_brokerage_list = False
    parsing_data_providers_list = False
    brokerages = []
    data_providers = []
    for line in yaml:
        # When you hit the CreateLiveAlgorithmRequest schema, start 
        # processing it.
        if line.strip() == 'CreateLiveAlgorithmRequest:':
            parsing_request = True
            continue
        # If you haven't started processing the 
        # CreateLiveAlgorithmRequest schema yet, just continue to the 
        # next line.
        if not parsing_request:
            continue

        # Get the number of leading white space in this line of the 
        # YAML.
        line_indents = indents(line)

        # When you hit the "brokerage" property of the 
        # CreateLiveAlgorithmRequest, start inspecting each line.
        if 'brokerage:' in line:
            parsing_brokerage_list = True
            brokerage_indents = line_indents
            continue
        # If you are processing the "brokerage" property...
        if parsing_brokerage_list:
            # When the brokerages property closes, stop inspecting 
            # lines.
            if line_indents <= brokerage_indents:
                parsing_brokerage_list = False
            # Otherwise, record each brokerage that the endpoint 
            # supports.
            elif '$ref' in line:
                schema_name = line.split('/')[-1].rstrip()[:-1]
                brokerages.append(Brokerage(schema_name))

        # When you hit the "dataProviders" property of the 
        # CreateLiveAlgorithmRequest, start inspecting each line.
        if 'dataProviders:' in line:
            parsing_data_providers_list = True
            data_provider_indents = line_indents
            continue
        # If you are processing the "dataProviders" property...
        if parsing_data_providers_list:
            # When the dataProviders property closes, stop inspecting 
            # lines.
            if line_indents <= data_provider_indents:
                parsing_data_providers_list = False
                break
            elif line_indents == data_provider_indents + 4:
                key = line.strip()[:-1]
            # Otherwise, record each data provider that the endpoint 
            # supports.
            elif '$ref' in line:
                schema_name = line.split('/')[-1].rstrip()[:-1]
                data_providers.append(DataProvider(key, schema_name))

    return brokerages, data_providers

# Define a method to parse the properties of each brokerage and data 
# provider.
def load_schema(model, yaml):
    parsing_schema = False
    parsing_required = False
    parsing_properties = False
    for line in yaml:
        # When you hit the schema name, start processing it.
        if line.strip() == f'{model.schema_name}:':
            parsing_schema = True
            continue
        # If you haven't started processing the schema yet, just 
        # continue to the next line.
        if not parsing_schema:
            continue

        # Get the number of leading white space in this line of the 
        # YAML.
        line_indents = indents(line)
        line = line.strip()

        if line == 'required:':
            parsing_required = True
            continue
        if line == 'properties:':
            parsing_required = False
            parsing_properties = True
            property_indents = line_indents
            continue

        if parsing_required:
            property_name = line.split(' ')[-1]
            model.make_property_required(property_name)

        if parsing_properties:

            # When we reach the end of the properties, stop.
            if line_indents <= property_indents:
                parsing_enums = False
                break

            # If this line holds the name of a property...
            if line_indents == property_indents + 2:
                # Add the property to the model.
                property_name = line[:-1]
                model.add_property(property_name)
                # Set the `parsing_enums` flag for this property.
                parsing_enums = False
            
            # If this line holds the type of a property...
            elif line.split(' ')[0] == 'type:':
                model.set_property_type(line.split(' ')[1])

            elif line.split(' ')[0] == 'enum:':
                parsing_enums = True

            elif parsing_enums:
                enum = line.split(' ')[-1]
                model.add_property_enum(enum)

if __name__ == '__main__':
    # Read the YAML file.
    #yaml = QuantBook().download(YAML_PATH).splitlines()
    #with open(YAML_PATH, 'r') as file:
    #    yaml = file.readlines()
    yaml = requests.get(YAML_URL).text.splitlines()

    # Get the brokerage and data providers that the /live/create 
    # endpoint supports.
    brokerages, data_providers = get_supported_models(yaml)

    for model_type, models in zip(['brokerage', 'data provider'], [brokerages, data_providers]):
        for j, model in enumerate(models):
            schemas_to_skip = [
                # Skip the Alpaca brokerage. The `required` properties in the YAML
                # aren't exactly correct, since it can be used with auth or not.
                'AlpacaBrokerageSettings',
                # Wolverine brokerage is currently under maintance.
                'WolverineSettings',
                # Need permission to use RBI brokerage
                'RBIBrokerageSettings'
            ]
            if model.schema_name in schemas_to_skip:
                continue  

            print(f'{j+1}/{len(models)}:', model.schema_name)
            project_id, compile_id, node_id = prepare_live_payload()
            load_schema(model, yaml)
            # Define a payload for the model settings using only the required
            # properties.
            minimal_model_payload = {}
            for p in model.properties:
                if p.name not in model.required_properties:
                    continue
                if p.enums:
                    value = p.enums[0]
                elif p.name in VALUE_OVERRIDE_BY_PROPERTY:
                    value = VALUE_OVERRIDE_BY_PROPERTY[p.name]
                else:
                    value = DEFAULT_VALUE_BY_TYPE[p.type]
                minimal_model_payload[p.name] = value
            # Ensure the endpoint fails when we omit each of the required 
            # properties.
            for p_name in model.required_properties:
                model_payload = {
                    k: v 
                    for k, v in minimal_model_payload.items() if k != p_name
                }
                print(f'> {model_type} payload:', model_payload)
                if model_type == 'brokerage':
                    brokerage = model_payload
                    data_providers = DEFAULT_DATA_PROVIDERS
                else:
                    brokerage = DEFAULT_BROKERAGE
                    data_providers = {model.key: model_payload}
                response = create_live_algorithm(
                    project_id, compile_id, node_id, brokerage, data_providers
                )
                print('--> response:', response)
                assert not response['success'], "Trim down required properties"
            
            # Ensure the endpoint successed when we include all the 
            # required properties.
            print(f'> {model_type} payload:', minimal_model_payload)
            if model_type == 'brokerage':
                brokerage = minimal_model_payload
                data_providers = DEFAULT_DATA_PROVIDERS
            else:
                brokerage = DEFAULT_BROKERAGE
                data_providers = {model.key: minimal_model_payload}
            response = create_live_algorithm(
                project_id, compile_id, node_id, brokerage, data_providers
            )
            print('--> response:', response, '\n')
            assert response['success'], "Expand required properties"
            
            # Stop the live algorithm.
            post('/live/update/stop', {'projectId': project_id})
        
            # Delete the project to clean up.
            post('/projects/delete', {'projectId': project_id})
