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



class ExampleRefTests(unittest.TestCase):

    def _example(self, schemas, properties):
        with tempfile.NamedTemporaryFile("w", suffix=".yaml", delete=False) as f:
            yaml.safe_dump({"components": {"schemas": schemas}}, f)
        self.addCleanup(pathlib.Path(f.name).unlink)
        json_str, _, _ = _module.APIReferenceGenerator(f.name)._build_properties(properties, [])
        return json.loads(json_str)

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


if __name__ == "__main__":
    unittest.main()
