// Use UniverseManager to find symbols from the Universe Selection
foreach (var universe in UniverseManager.Values)
{    
    // User defined universe has symbols from AddSecurity/AddEquity calls
    if (universe is UserDefinedUniverse)
    {
        continue;
    }
    var symbols = universe.Members.Keys;
}