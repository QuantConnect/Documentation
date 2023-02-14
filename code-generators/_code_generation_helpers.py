from json import dumps
from re import findall
from typing import List
from urllib.request import urlopen

WRITING_ALGORITHMS = '03 Writing Algorithms'
INDICATORS = f'{WRITING_ALGORITHMS}/28 Indicators/01 Supported Indicators'
API_REFERENCE = f'{WRITING_ALGORITHMS}/98 API Reference/'

def get_text_content(url: str) -> str:
    return urlopen(url).read().decode('utf-8')

def get_json_content(url: str) -> List:
    content = get_text_content(url) \
        .replace("null", "None").replace("true", "True").replace("false", "False")
    return eval(content)

def get_type(_type: str) -> List:
    url = f'https://www.quantconnect.com/services/inspector?type=T:{_type}'
    return get_json_content(url)

def to_key(name: str) -> str:
    key = name
    if not key.isupper():
        key = '-'.join(findall('[a-zA-Z][^A-Z]*', name))
    return key.lower()