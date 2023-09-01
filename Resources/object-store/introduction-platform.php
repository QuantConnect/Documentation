<p>The Object Store is an organization-specific key-value storage location to save and retrieve data<?=$cloudPlatform ? " in QuantConnect's cache" : ""?>. Similar to a dictionary or hash table, a key-value store is a storage system that saves and retrieves objects by using keys. A key is a unique string that is associated with a single record in the key-value store and a value is an object being stored. Some common use cases of the Object Store include the following:</p>

<ul>
    <li>Transporting data between the backtesting environment and the research environment.</li>
    <li>Training machine learning models in the research environment before deploying them to live trading.</li>
</ul>

<p>The Object Store is shared across the entire organization. Using the same key, you can access data across all projects in an organization.</p>