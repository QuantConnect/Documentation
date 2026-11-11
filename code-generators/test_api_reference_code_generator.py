"""Tests for API-Reference-Code-Generator.py.

Usage:
    python -m unittest discover -s code-generators -p "test_*.py"
"""

import importlib.util
import pathlib
import tempfile
import unittest

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
            "Result": {
                "type": "object",
                "required": ["requiredNullable", "requiredPlain"],
                "properties": {
                    "requiredNullable": {"type": ["integer", "null"]},
                    "requiredPlain": {"type": "integer"},
                    "optionalNullable": {"type": ["string", "null"]},
                    "optionalPlain": {"type": "string"},
                    "nullableArray": {"type": ["array", "null"], "items": {"type": "string"}},
                    "nullableRef": {"oneOf": [{"$ref": "#/components/schemas/Detail"}, {"type": "null"}]},
                    "plainRef": {"$ref": "#/components/schemas/Detail"},
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


if __name__ == "__main__":
    unittest.main()
