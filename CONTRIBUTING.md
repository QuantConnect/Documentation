# Contributing
Refer to Lean [Contributing](https://github.com/QuantConnect/Lean/blob/master/CONTRIBUTING.md#contributing) for details.

# Documentation Pre Hooks
The following table describes some global PHP variables you may use:
| Variable  | Description  |
|---|---|
| `BREADCRUMBS`  | An `Array<String>` where each element represents a level of the breadcrumbs. For example, ["cloud-platform","organizations","data-storage"].  |
| `ANCHOR`  | A string that represents the partial URL of an `<h3>` tag in the documentation. For example, "#remove-option-contract".  |
| `DOCS_RESOURCES`  | String path to the [docs Resources folder](https://github.com/QuantConnect/Documentation/tree/master/Resources).  |
| `DOCS_ROOT` | String path to the [docs root folder](https://github.com/QuantConnect/Documentation).  |
| `DOCS_URL` | A function that returns part of the URL. For example, `DOCS_URL() => "cloud-platform/organizations/object-store"` and `DOCS_URL(0) => "cloud-platform"`.  
