<p>The Object Store is an organization-specific key-value storage location to save and retrieve data<?=$cloudPlatform ? " in QuantConnect's cache" : ""?>. Similar to a dictionary or hash table, a key-value store is a storage system that saves and retrieves objects by using keys. A key is a unique string that is associated with a single record in the key-value store and a value is an object being stored. Some common use cases of the Object Store include the following:</p>

<ul>
    <li>Transporting data between the backtesting environment and the research environment.</li>
    <li>Training machine learning models in the research environment before deploying them to live trading.</li>
</ul>

<p>The Object Store is shared across the entire organization. Using the same key, you can access data across all projects in an organization.</p>
<? if ($cloudPlatform) { ?>

<p>The Object Store is available to paid organizations. In Free organizations, algorithms that try to save data log "The current user does not have permission to write to the organization Object Store". To use the Object Store, <a href='/docs/v2/cloud-platform/organizations/billing#07-Change-Organization-Tiers'>upgrade your organization</a> to a paid tier.</p>
<? } ?>