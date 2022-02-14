import pathlib
import yaml

documentations = {"Our Platform": "QuantConnect-Platform-2.0.0.yaml",
                  "Alpha Streams": "QuantConnect-Alpha-0.8.yaml"}

def RequestTable(api_call, params):
    writeUp = '<table class="table qc-table">\n<thead>\n<tr>\n'
    writeUp += f'<th colspan="2"><code>{api_call}</code> Method</th>\n</tr>\n</thead>'
    example = '<tr>\n<td width="20%">Example</td>\n<td>\n<div class="cli section-example-container"><pre>\n{\n'
    
    for item in params:
        example_ = "/"
        
        description_ = "Optional. " if "required" not in item or not item["required"] else ""
        description_ += item["description"]
        
        if description_[-1] != ".":
              description_ += "."
              
        if "type" in item["schema"]:
            type_ = item["schema"]["type"]
        else:
            type_ = item["schema"]["$ref"].split("/")[-1]
            
        if "minimum" in item["schema"]:
            description_ += f' Minimum: {item["schema"]["minimum"]}'
            example_ = item["schema"]["minimum"]
            
        elif "maximum" in item["schema"]:
            description_ += f' Maximum: {item["schema"]["maximum"]}'
            example_ = item["schema"]["maximum"]
            
        elif "default" in item["schema"]:
            description_ += f' Default: {item["schema"]["default"]}'
            example_ = item["schema"]["default"]
            
        if type_ == "array":
            array_obj = item["schema"]["items"]
            
            if "$ref" in array_obj:
                type_ = array_obj["$ref"].split("/")[-1] + " Array"
                
                ref = array_obj["$ref"].split("/")[1:]
                type_ = ref[-1] + " Array"
                
                request_object_ = doc
                for path in ref:
                    request_object_ = request_object_[path]
                    
                if "properties" in request_object_:
                    request_object_properties_ = request_object_["properties"]
                    example_, __, __ = ExampleWriting(request_object_properties_, [], 1)
            
            if "type" in array_obj:
                type_ = array_obj["type"] + " Array"
                
            if "enum" in array_obj:
                type_ = type_ + " Enum"
                description_ += f' Options: {str(array_obj["enum"])}'
                example_ = f'"{array_obj["enum"][0]}"'
            
        if "Enum" not in type_:
            if "string" in type_:
                example_ = '"string"'
                
            elif "number" in type_ or "integer" in type_:
                example_ = '0'
                
            elif "boolean" in type_:
                example_ = 'true'
        
        writeUp += f'\n<tr>\n<td width="20%">{item["name"]}</td> <td> <code>{type_}</code><br/>{description_}</td>\n</tr>'
        example += f'  "{item["name"]}": {example_},\n'
        
    return writeUp + example + "\b}</pre>\n</div>\n</td>\n</tr>\n</table>"

def ResponseTable(requestBody):
    writeUp = ""
    array = False
    order = 0
    
    if "content" in requestBody:
        component = requestBody["content"]["application/json"]["schema"]
        
        if "$ref" in component:
            component = component["$ref"].split("/")[1:]
            
        elif "items" in component and "$ref" in component["items"]:
            component = component["items"]["$ref"].split("/")[1:]
            array = True
            order += 1
            
        else:
            writeUp += '<table class="table qc-table">\n<thead>\n<tr>\n'
            writeUp += f'<th colspan="2">{requestBody["description"]}</th>\n'
            writeUp += '</tr>\n</thead>\n'
            
            writeUp += f'<tr>\n<td width="20%">value</td> <td> <code>{component["items"]["type"]}</code> <br/>/</td>\n</tr>\n'
            
            writeUp += '<tr>\n<td width="20%">Example</td>\n<td>\n<div class="cli section-example-container"><pre>\n'
            writeUp += f'[\n  "{component["items"]["example"]}"\n]'
            writeUp += '</pre>\n</div>\n</td>\n</tr>\n</table>'
            
            return writeUp
            
    else:
        component = requestBody["$ref"].split("/")[1:]
        
    item_list = [component]
    i = 0
    
    while i < len(item_list):
        request_object = doc
        for item in item_list[i]:
            request_object = request_object[item]
            
        if "items" in request_object and "oneOf" in request_object["items"]:
            prop = request_object["items"]["oneOf"]
            example = '<tr>\n<td width="20%">Example</td>\n<td>\n<div class="cli section-example-container"><pre>\n[\n  ['
            
            writeUp += '<table class="table qc-table">\n<thead>\n<tr>\n'
            writeUp += f'<th colspan="2"><code>{item}</code> Model - {request_object["description"]}</th>\n'
            writeUp += '</tr>\n</thead>'
            
            for y in prop:
                path = y["$ref"].split("/")[1:]
                name = path[-1]
                if name[0] == "_": continue

                enum = ""
                item_list.append(path)
                
                request_object = doc
                for item in path:
                    request_object = request_object[item]
                    
                if "enum" in request_object:
                    enum = " Options: " + str(request_object["enum"])
                
                description_ = request_object["description"]
                if description_[-1] != ".":
                    description_ += "."
                    
                writeUp += f'\n<tr>\n<td width="20%">{name}</td> <td> <code>{request_object["type"]}</code> <br/> {description_ + enum}</td>\n</tr>\n'

                if "example" in request_object:
                    text = request_object["example"]
                elif "enum" in request_object:
                    text = '"' + request_object["enum"][0] + '"'
                    
                example += f'\n    {text},'
                
            example += '\b\n  ]\n]'
            writeUp += example
            writeUp += '</pre>\n</div>\n</td>\n</tr>\n</table>'
                
            i += 1
            continue
        
        elif "oneOf" in request_object:
            for y in request_object["oneOf"]:
                item_list.append(y["$ref"].split("/")[1:])
            i += 1
            continue
            
        elif "properties" in request_object:
            request_object_properties = request_object["properties"]
            
        elif "content" in request_object:
            item_list.append(request_object["content"]["application/json"]["schema"]["$ref"].split("/")[1:])
            i += 1
            continue
        
        elif "type" in request_object and "properties" not in request_object:
            request_object_properties = {item: request_object}
        
        writeUp += '<table class="table qc-table">\n<thead>\n<tr>\n'
        
        if "description" in request_object:
            writeUp += f'<th colspan="2"><code>{item_list[i][-1]}</code> Model - {request_object["description"]}</th>\n'
            
        else:
            writeUp += f'<th colspan="2"><code>{item_list[i][-1]}</code> Model</th>\n'
            
        writeUp += '</tr>\n</thead>\n'
        
        example, html_property, item_list = ExampleWriting(request_object_properties, item_list, array, order)
        
        if array:
            array = False
            order -= 1
        
        for line in html_property:
            writeUp += line
            
        writeUp += '<tr>\n<td width="20%">Example</td>\n<td>\n<div class="cli section-example-container"><pre>\n'
        writeUp += example
        writeUp += '</pre>\n</div>\n</td>\n</tr>\n</table>'
        
        i += 1
        
    return writeUp

def ExampleWriting(request_object_properties, item_list, array=False, order=0):
    tab = "  " * order
    if array:
        example = "[\n  {\n"
    else:
        example = "{\n"
    line = []
    
    for name, properties in request_object_properties.items():
        if name[0] == "_": 
            name = name[1:].title()

        type_ = properties["type"] if "type" in properties else "object"
        description_ = properties["description"] if "description" in properties else "/"
        
        if (example != "{\n" and not array) or (example != "[\n  {\n" and array):
            example += ",\n"
        example_ = tab + f'  "{name}": '
        
        if type_ == "array":
            example_ += '[\n'
            
            if "type" in properties["items"]:
                type_ = properties["items"]["type"] + " Array"
                example_ += tab + f'    "{properties["items"]["type"]}"'
                
            elif "$ref" in properties["items"]:
                ref = properties["items"]["$ref"].split("/")[1:]
                type_ = ref[-1] + " Array"
                
                if ref not in item_list:
                    item_list.append(ref)
                
                request_object_ = doc
                for item in ref:
                    request_object_ = request_object_[item]
                    
                if "properties" in request_object_:
                    request_object_properties_ = request_object_["properties"]
                    
                    write_up, __, item_list = ExampleWriting(request_object_properties_, item_list, order=order+2)
                    example_ += tab + "  " * 2 + write_up
        
        elif type_ == "object":
            if "additionalProperties" in properties:
                add_prop = properties["additionalProperties"]
                
                if "type" in add_prop:
                    prop_type = add_prop["type"]
                    
                    if "format" in prop_type:
                        type_ = prop_type + f'$({prop_type["format"]})' + " object"
                        
                        if prop_type["format"] == "date-time":
                            example_ += "2021-11-26T15:18:27.693Z"
                            
                        else:
                            example_ += "0"
                            
                    else:
                        type_ = prop_type + " object"
                        example_ += f'"{prop_type}"'
                        
                elif "$ref" in add_prop:
                    ref = add_prop["$ref"].split("/")[1:]
                    type_ = ref[-1] + " object"
                    
                    if ref not in item_list:
                        item_list.append(ref)
                    
                    request_object_ = doc
                    for item in ref:
                        request_object_ = request_object_[item]
                        
                    if "properties" in request_object_:
                        request_object_properties_ = request_object_["properties"]
                        
                        write_up, __, item_list = ExampleWriting(request_object_properties_, item_list, order=order+1)
                        example_ += write_up
            
            elif "$ref" in properties:
                ref = properties["$ref"].split("/")[1:]
                type_ = ref[-1] + " object"
                
                if ref not in item_list:
                    item_list.append(ref)
                
                request_object_ = doc
                for item in ref:
                    request_object_ = request_object_[item]
                    
                if "properties" in request_object_:
                    request_object_properties_ = request_object_["properties"]
                    description_ = request_object_["description"] if "description" in request_object_ else "/"
                    
                    write_up, __, item_list = ExampleWriting(request_object_properties_, item_list, order=order+1)
                    example_ += write_up
                    
                elif "type" in request_object_:
                    properties = request_object_properties_ = request_object_
                    type_ = request_object_["type"]
                    description_ = request_object_["description"] if "description" in request_object_ else "/"
            
        elif type_ == "integer" or type_ == "number":
            example_ += "0"
            
        elif type_ == "boolean":
            example_ += "true"
            
        elif type_ == "string":
            if "format" in properties:
                type_ += f'(${properties["format"]})'
                example_ += "2021-11-26T15:18:27.693Z"
                
            else:
                example_ += '"string"'
        
        if description_[-1] != ".":
              description_ += "."
              
        if "enum" in properties:
            type_ += " Enum"
            description_ += f' Options : {properties["enum"]}'
            
            if "string" in type_:
                example_ = tab + f'  "{name}": "{properties["enum"][0]}"'
                
            else:
                example_ = tab + f'  "{name}": {properties["enum"][0]}'
            
        if "example" in properties:
            eg = properties["example"]
            type_ += f'<br/><i><sub>example: {eg}</sub></i>'
            
            if isinstance(eg, str):
                eg = '"' + eg + '"'
            example_ = tab + f'  "{name}": {eg}'
        
        if "Array" in type_:
            example_ += "\n" + tab + "  ]"
        
        if order == 0 or array:
            line.append(f'<tr>\n<td width="20%">{name}</td> <td> <code>{type_}</code> <br/> {description_}</td>\n</tr>\n')
            
        example += example_
    
    if not array:
        return example + "\n" + tab + "}", line, item_list
    
    return example + "\n" + tab + "}\n" + "  " * (order-1) + "]", line, item_list

for section, source in documentations.items():
    yaml_file = open(source)
    doc = yaml.load(yaml_file, Loader=yaml.Loader)
    paths = doc["paths"]

    for api_call, result in paths.items():
        j = 1
        content = result["post"] if "post" in result else result["get"]
        
        # Create path if not exist
        link = "/".join(content["tags"])
        destination_folder = pathlib.Path(link)
        destination_folder.mkdir(parents=True, exist_ok=True)
        
        # Create Introduction part
        with open(destination_folder / f'{j:02} Introduction.html', "w") as html_file:
            html_file.write("<p>\n")
            html_file.write(f"{content['summary']}\n")
            html_file.write("</p>\n")
            
            j += 1
            
        # Create Description part if having one
        if "description" in content:
            with open(destination_folder / f'{j:02} Description.html', "w") as html_file:
                html_file.write('<p>\n')
                html_file.write(f'{content["description"]}\n')
                html_file.write('</p>\n')
                
                j += 1
            
        # Create Request part
        with open(destination_folder / f'{j:02} Request.html', "w") as html_file:
            description_ = ""
                
            if "parameters" in content:
                writeUp = RequestTable(api_call, content["parameters"])
                
            elif "requestBody" in content:
                if "description" in content["requestBody"]:
                    description_ = str(content["requestBody"]["description"]) 
                    
                    if description_[-1] != ".":
                        description_ += "."
                        
                    description_ += " "
                    
                writeUp = ResponseTable(content["requestBody"])
                
            else:
                writeUp = '<table class="table qc-table">\n<thead>\n<tr>\n'
                writeUp += f'<th colspan="1"><code>{api_call}</code> Method</th>\n</tr>\n</thead>\n'
                writeUp += f'</tr>\n<td><code>{api_call}</code> method takes no parameters.</td>\n</tr>\n</table>'
            
            description_ += f'The <code>{api_call}</code> API accepts requests in the following format:\n'
            
            html_file.write("<p>\n" + description_ + "</p>\n")
            html_file.write(writeUp)
                
            j += 1

        # Create Response part
        with open(destination_folder / f'{j:02} Responses.html', "w") as html_file:
            html_file.write('<p>\n')
            html_file.write(f'The <code>{api_call}</code> API provides a response in the following format:\n')
            html_file.write('</p>\n')
            
            request_body = content["responses"]
            for code, properties in request_body.items():
                if code == "200":
                    html_file.write('<h4>200 Success</h4>\n')
                    
                elif code == "401":
                    html_file.write('<h4>401 Authentication Error</h4>\n<table class="table qc-table">\n<thead>\n<tr>\n')
                    html_file.write('<th colspan="2"><code>UnauthorizedError</code> Model - Unauthorized response from the API. Key is missing, invalid, or timestamp is too old for hash.</th>\n')
                    html_file.write('</tr>\n</thead>\n<tr>\n<td width="20%">www_authenticate</td> <td> <code>string</code> <br/> Header</td>\n</tr>\n</table>\n')
                    continue
                
                elif code == "404":
                    html_file.write('<h4>404 Not Found Error</h4>\n')
                    html_file.write('<p>The requested item, index, page was not found.</p>\n')
                    continue
                    
                elif code == "default":
                    html_file.write('<h4>Default Generic Error</h4>\n')
            
                writeUp = ResponseTable(properties)
                html_file.write(writeUp)
                
    print(f"Documentation of {section} is generated and inplace!")