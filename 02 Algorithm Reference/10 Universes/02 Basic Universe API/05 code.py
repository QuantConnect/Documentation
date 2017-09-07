# Use UniverseManager to find symbols from the Universe Selection
for universe in self.UniverseManager.Values:

    # User defined universe has symbols from AddSecurity/AddEquity calls
    if universe is UserDefinedUniverse:
        continue
    
    symbols = universe.Members.Keys