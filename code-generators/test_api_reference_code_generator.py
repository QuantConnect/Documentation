"""Tests for API-Reference-Code-Generator.py.

Usage:
    python -m unittest discover -s code-generators -p "test_*.py"
"""

import importlib.util
import json
import pathlib
import tempfile
import unittest

import jsonschema
import yaml

_spec = importlib.util.spec_from_file_location(
    "api_reference_code_generator", pathlib.Path(__file__).parent / "API-Reference-Code-Generator.py"
)
_module = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(_module)

SPEC = {
    "components": {
        "schemas": {
            "Detail": {"type": "object", "properties": {"name": {"type": "string"}}},
            "Mode": {"type": "string", "enum": ["fast", "slow"]},
            "Result": {
                "type": "object",
                "required": ["requiredNullable", "requiredPlain", "requiredPrimitiveRef"],
                "properties": {
                    "requiredNullable": {"type": ["integer", "null"]},
                    "requiredPlain": {"type": "integer"},
                    "optionalNullable": {"type": ["string", "null"]},
                    "optionalPlain": {"type": "string"},
                    "nullableArray": {"type": ["array", "null"], "items": {"type": "string"}},
                    "nullableRef": {"oneOf": [{"$ref": "#/components/schemas/Detail"}, {"type": "null"}]},
                    "plainRef": {"$ref": "#/components/schemas/Detail"},
                    "requiredPrimitiveRef": {"$ref": "#/components/schemas/Mode"},
                    "optionalPrimitiveRef": {"$ref": "#/components/schemas/Mode"},
                },
            },
        }
    }
}

NULLABLE = "<i><sub>nullable</sub></i>"
REQUIRED = "<i><sub>required</sub></i>"


class NullableTypeTests(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        with tempfile.TemporaryDirectory() as folder:
            path = pathlib.Path(folder) / "spec.yaml"
            path.write_text(yaml.safe_dump(SPEC))
            generator = _module.APIReferenceGenerator(path)
        table = generator._build_schema_tables({"$ref": "#/components/schemas/Result"})
        # {property name: type cell of its row}
        cls.cells = {
            row.split("</td>")[0]: row.split("<code>")[1].split("</code>")[0]
            for row in table.split('<td width="20%">')[1:]
            if "<code>" in row
        }

    def assert_cell(self, name, type_label, required, nullable):
        cell = self.cells[name]
        self.assertTrue(cell.startswith(type_label), cell)
        self.assertEqual(REQUIRED in cell, required, cell)
        self.assertEqual(NULLABLE in cell, nullable, cell)

    def test_required_nullable_scalar(self):
        self.assert_cell("requiredNullable", "integer", required=True, nullable=True)

    def test_required_non_nullable_scalar(self):
        self.assert_cell("requiredPlain", "integer", required=True, nullable=False)

    def test_optional_nullable_scalar(self):
        self.assert_cell("optionalNullable", "string", required=False, nullable=True)

    def test_optional_non_nullable_scalar(self):
        self.assert_cell("optionalPlain", "string", required=False, nullable=False)

    def test_nullable_array(self):
        self.assert_cell("nullableArray", "string Array", required=False, nullable=True)

    def test_nullable_ref(self):
        self.assert_cell("nullableRef", "Detail object", required=False, nullable=True)

    def test_non_nullable_ref(self):
        self.assert_cell("plainRef", "Detail object", required=False, nullable=False)

    def test_required_primitive_ref(self):
        self.assert_cell("requiredPrimitiveRef", "string Enum", required=True, nullable=False)

    def test_optional_primitive_ref(self):
        self.assert_cell("optionalPrimitiveRef", "string Enum", required=False, nullable=False)



class ExampleTestCase(unittest.TestCase):

    def _example(self, schemas, properties):
        with tempfile.NamedTemporaryFile("w", suffix=".yaml", delete=False) as f:
            yaml.safe_dump({"components": {"schemas": schemas}}, f)
        self.addCleanup(pathlib.Path(f.name).unlink)
        json_str, _, _ = _module.APIReferenceGenerator(f.name)._build_properties(properties, [])
        return json.loads(json_str)


class ExampleRefTests(ExampleTestCase):

    def test_ref_to_oneof_schema_conforms_to_schema(self):
        parameter_set = {
            "oneOf": [
                {"type": "array", "maxItems": 0},
                {
                    "type": "object",
                    "additionalProperties": {
                        "oneOf": [{"type": "string"}, {"type": "number"}, {"type": "integer"}]
                    },
                },
            ]
        }
        example = self._example(
            {"ParameterSet": parameter_set},
            {"parameterSet": {"$ref": "#/components/schemas/ParameterSet"}},
        )
        self.assertEqual({"parameterSet": {"key": "string"}}, example)
        jsonschema.validate(example["parameterSet"], parameter_set)

    def test_ref_to_oneof_schema_without_object_variant_uses_first_variant(self):
        schemas = {"Name": {"oneOf": [{"type": "string"}, {"type": "null"}]}}
        example = self._example(schemas, {"name": {"$ref": "#/components/schemas/Name"}})
        self.assertEqual({"name": "string"}, example)

    def test_ref_to_object_schema_without_properties_is_empty_object(self):
        schemas = {"Authentication": {"type": "object"}}
        example = self._example(schemas, {"authentication": {"$ref": "#/components/schemas/Authentication"}})
        self.assertEqual({"authentication": {}}, example)

    def test_object_without_properties_is_empty_object(self):
        example = self._example({}, {"timeInForce": {"type": "object"}})
        self.assertEqual({"timeInForce": {}}, example)

    def test_ref_to_enum_schema_uses_first_value(self):
        schemas = {"Status": {"enum": ["new", "running"]}}
        example = self._example(schemas, {"status": {"$ref": "#/components/schemas/Status"}})
        self.assertEqual({"status": "new"}, example)


class ScalarExampleTests(ExampleTestCase):

    def test_string_example_with_quotes_is_escaped(self):
        text = 'Detected near "self.set_start_date(2024, 1, 3)".'
        example = self._example({}, {"type": {"type": "string", "examples": [text]}})
        self.assertEqual({"type": text}, example)

    def test_string_example_containing_array_has_no_closing_bracket(self):
        example = self._example({}, {"payloadType": {"type": "string", "examples": ["StringArray"]}})
        self.assertEqual({"payloadType": "StringArray"}, example)

    def test_boolean_example_is_json_boolean(self):
        example = self._example({}, {"write": {"type": "boolean", "examples": [False]}})
        self.assertEqual({"write": False}, example)

    def test_array_item_example_is_escaped(self):
        prop = {"type": "array", "items": {"type": "string", "examples": ['say "hi"']}}
        example = self._example({}, {"lines": prop})
        self.assertEqual({"lines": ['say "hi"']}, example)

    def test_integer_array_item_is_integer(self):
        example = self._example({}, {"orderIds": {"type": "array", "items": {"type": "integer"}}})
        self.assertEqual({"orderIds": [0]}, example)

    def test_object_array_item_is_empty_object(self):
        example = self._example({}, {"notifications": {"type": "array", "items": {"type": "object"}}})
        self.assertEqual({"notifications": [{}]}, example)


class ShapeExampleTests(ExampleTestCase):

    def test_map_of_ref_values_has_key_level(self):
        schemas = {"Chart": {"type": "object", "properties": {"name": {"type": "string"}}}}
        prop = {"type": "object", "additionalProperties": {"$ref": "#/components/schemas/Chart"}}
        example = self._example(schemas, {"charts": prop})
        self.assertEqual({"charts": {"key": {"name": "string"}}}, example)

    def test_inline_oneof_uses_first_variant(self):
        values = {"oneOf": [{"type": "array", "items": {"type": "array"}}, {"type": "array", "items": {"type": "object"}}]}
        example = self._example({}, {"values": values})
        jsonschema.validate(example["values"], values)


class PlatformSpecParameterTests(unittest.TestCase):

    VALUES = ["20", 20, 20.5, True, False, None]

    @classmethod
    def setUpClass(cls):
        path = pathlib.Path(__file__).parent.parent / "QuantConnect-Platform-2.0.0.yaml"
        cls.schemas = yaml.safe_load(path.read_text())["components"]["schemas"]

    def assert_accepts_scalar_values(self, schema):
        for value in self.VALUES:
            with self.subTest(value=value):
                jsonschema.validate({"key": value}, schema)

    def test_parameter_set_accepts_scalar_values(self):
        self.assert_accepts_scalar_values(self.schemas["ParameterSet"])

    def test_parameter_set_accepts_empty_list(self):
        jsonschema.validate([], self.schemas["ParameterSet"])

    def test_create_backtest_parameters_accept_scalar_values(self):
        self.assert_accepts_scalar_values(self.schemas["CreateBacktestRequest"]["properties"]["parameters"])


class AllOfSchemaTests(unittest.TestCase):

    def test_allof_response_merges_properties(self):
        schemas = {
            "RestResponse": {"type": "object", "properties": {"success": {"type": "boolean"}}},
            "Detail": {"type": "object", "properties": {"name": {"type": "string"}}},
            "DetailResponse": {
                "allOf": [
                    {"$ref": "#/components/schemas/RestResponse"},
                    {
                        "type": "object",
                        "required": ["detail"],
                        "properties": {"detail": {"$ref": "#/components/schemas/Detail"}},
                    },
                ],
                "description": "Response wrapping a detail.",
            },
        }
        with tempfile.NamedTemporaryFile("w", suffix=".yaml", delete=False) as f:
            yaml.safe_dump({"components": {"schemas": schemas}}, f)
        self.addCleanup(pathlib.Path(f.name).unlink)
        response = {"content": {"application/json": {"schema": {"$ref": "#/components/schemas/DetailResponse"}}}}
        tables = _module.APIReferenceGenerator(f.name)._build_schema_tables(response).split("</table>")[:-1]

        self.assertEqual(2, len(tables))
        self.assertIn("<code>DetailResponse</code> Model - Response wrapping a detail.", tables[0])
        self.assertIn('<td width="20%">success</td> <td> <code>boolean</code>', tables[0])
        self.assertIn(f'<td width="20%">detail</td> <td> <code>Detail object<br/>{REQUIRED}</code>', tables[0])
        example = tables[0].split("<pre>\n")[1].split("</pre>")[0]
        self.assertEqual({"success": True, "detail": {"name": "string"}}, json.loads(example))
        self.assertIn("<code>Detail</code> Model", tables[1])


if __name__ == "__main__":
    unittest.main()
