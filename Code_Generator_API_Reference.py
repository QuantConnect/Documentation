import pathlib
import urllib.request
import yaml

link = urllib.request.urlopen("https://raw.githubusercontent.com/QuantConnect/Documentation/master/QuantConnect-Platform-2.0.0.yaml")
doc = yaml.load(link, Loader=yaml.Loader)
paths = doc["paths"]

def TableCreation(requestBody):
    writeUp = ""
    component = requestBody["content"]["application/json"]["schema"]["$ref"].split("/")[1:]
    item_list = [component]
    i = 0
    
    while i < len(item_list):
        request_object = doc
        for item in item_list[i]:
            request_object = request_object[item]
            
        if "oneOf" in request_object:
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
            i += 1
            continue
        
        writeUp += '<table class="table qc-table">\n<thead>\n<tr>\n'
        if "description" in request_object:
            writeUp += f'<th colspan="2"><code>{item_list[i][-1]}</code> Model - {request_object["description"]}</th>\n'
        else:
            writeUp += f'<th colspan="2"><code>{item_list[i][-1]}</code> Model</th>\n'
        writeUp += '</tr>\n</thead>\n'
        
        example, html_property, item_list = ExampleWriting(request_object_properties, item_list)
        
        for line in html_property:
            writeUp += line
            
        writeUp += '<tr>\n<td width="20%">Example</td>\n<td>\n<div class="cli section-example-container"><pre>\n'
        writeUp += example
        writeUp += '</pre>\n</div>\n</td>\n</tr>\n</table>'
        
        i += 1
        
    return writeUp

def ExampleWriting(request_object_properties, item_list, order=0):
    tab = "  " * order
    example = "{\n"
    line = []
    
    for name, properties in request_object_properties.items():
        type_ = properties["type"] if "type" in properties else "object"
        description_ = properties["description"] if "description" in properties else "/"
        if example != "{\n":
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
                    
                    write_up, __, item_list = ExampleWriting(request_object_properties_, item_list, order+2)
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
                        
                        write_up, __, item_list = ExampleWriting(request_object_properties_, item_list, order+1)
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
                    
                    write_up, __, item_list = ExampleWriting(request_object_properties_, item_list, order+1)
                    example_ += write_up
                    
                elif "type" in request_object_:
                    properties = request_object_properties_ = request_object_
                    type_ = request_object_["type"]
                    description_ = request_object_["description"] if "description" in request_object_ else "/"
            
        elif type_ == "integer":
            example_ += "0"
            
        elif type_ == "number":
            type_ += f'(${properties["format"]})'
            example_ += "0"
            
        elif type_ == "boolean":
            example_ += "true"
            
        elif type_ == "string":
            if "format" in properties:
                type_ += f'(${properties["format"]})'
                example_ += "2021-11-26T15:18:27.693Z"
            else:
                example_ += '"string"'
        
        if "enum" in properties:
            type_ += " Enum"
            description_ += f' Options : {properties["enum"]}'
            if "string" in type_:
                example_ = tab + f'  "{name}": "{properties["enum"][0]}"'
            else:
                example_ = tab + f'  "{name}": {properties["enum"][0]}'
            
        if "example" in properties:
            type_ += f'<br/><i><sub>example: {properties["example"]}</sub></i>'
            example_ = tab + f'  "{name}": {properties["example"]}'
        
        if "Array" in type_:
            example_ += "\n" + tab + "  ]"
        
        if order == 0:
            line.append(f'</tr>\n<td width="20%">{name}</td> <td> <code>{type_}</code> <br/> {description_}</td>\n</tr>\n')
            
        example += example_
    
    return example + "\n" + tab + "}", line, item_list

for api_call, result in paths.items():
    j = 1
    content = result["post"]
    
    # Create path if not exist
    destination_folder = pathlib.Path("/".join(content["tags"]))
    destination_folder.mkdir(parents=True, exist_ok=True)
    
    # Create Introduction part
    with open(destination_folder / f'{j:02} Introduction.html', "w") as html_file:
        html_file.write("<p>\n")
        html_file.write(f"  {content['summary']}\n")
        html_file.write("</p>\n")
        
        j += 1
        
    # Create Description part if having one
    if "description" in content:
        with open(destination_folder / f'{j:02} Descripion.html', "w") as html_file:
            html_file.write('<p>\n')
            html_file.write(f'  {content["description"]}\n')
            html_file.write('</p>\n')
            
            j += 1
        
    # Create Request part
    with open(destination_folder / f'{j:02} Request.html', "w") as html_file:
        html_file.write('<p>\n')
        html_file.write(f'The <code>{api_call}</code> API accepts requests in the following format:\n')
        html_file.write('</p>\n')
        
        request_body = content["requestBody"]
        writeUp = TableCreation(request_body)
        html_file.write(writeUp)
            
        j += 1

    # Create Response part
    with open(destination_folder / f'{j:02} Response.html', "w") as html_file:
        html_file.write('<p>\n')
        html_file.write(f'The <code>{api_call}</code> API accepts requests in the following format:\n')
        html_file.write('</p>\n\n<h4>200 Success</h4>\n')
        
        request_body = content["responses"]["200"]
        
        writeUp = TableCreation(request_body)
        html_file.write(writeUp)
            
        html_file.write('<h4>401 Authentication Error</h4>\n<table class="table qc-table">\n<thead>\n<tr>\n')
        html_file.write('<th colspan="2"><code>UnauthorizedError</code> Model - Unauthorized response from the API. Key is missing, invalid, or timestamp is too old for hash.</th>\n')
        html_file.write('</tr>\n</thead>\n<tr>\n<td width="20%">www_authenticate</td> <td> <code>string</code> <br/> Header</td>\n</tr>\n</table>')