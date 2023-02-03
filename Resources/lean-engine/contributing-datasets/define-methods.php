<li>Define the methods in your dataset class that define the <a href='/docs/v2/lean-engine/contributing-datasets/key-concepts#06-Resolutions'>dataset frequency</a>.</li>
<div class="section-example-container">
    <pre>public class VendorNameDatasetName<?=$classNameEnding?> : BaseData
{
    public override List&lt;Resolution&gt; SupportedResolutions()
    {
        return DailyResolution;
    }

    public override Resolution DefaultResolution()
    {
        return Resolution.Daily;
    }

    public override bool IsSparseData()
    {
        return true;
    }
}</pre>
</div>

<li>Define the <a href='/docs/v2/lean-engine/contributing-datasets/key-concepts#07-Time-Zones'>DataTimeZone</a> method.</li>
<div class="section-example-container">
    <pre>public class VendorNameDatasetName<?=$classNameEnding?> : BaseData
{
    public override DateTimeZone DataTimeZone()
    {
        return DateTimeZone.Utc;
    }
}</pre>
</div>

<li>Define the <a href='/docs/v2/lean-engine/contributing-datasets/key-concepts#08-Linked-Datasets'>RequiresMapping</a> method.</li>
<div class="section-example-container">
    <pre>public class VendorNameDatasetName<?=$classNameEnding?> : BaseData
{
    public override bool RequiresMapping()
    {
        return true;
    }
}</pre>
</div>

<li>Define the <a href='/docs/v2/lean-engine/contributing-datasets/key-concepts#09-String-Representations'>ToString</a> method.</li>
<div class="section-example-container">
    <pre>public class VendorNameDatasetName<?=$classNameEnding?> : BaseData
{
    public override string ToString()
    {
        return $"{Symbol} - {SomeCustomProperty}";
    }
}</pre>
</div>

<li>Define the <a href='/docs/v2/lean-engine/contributing-datasets/key-concepts#10-Clone-Objects'>Clone</a> method.</li>
<div class="section-example-container">
    <pre>public class VendorNameDatasetName<?=$classNameEnding?> : BaseData
{
    public override BaseData Clone()
    {
        return new VendorNameDatasetName
        {
            Symbol = Symbol,
            Time = Time,
            EndTime = EndTime,
            SomeCustomProperty = SomeCustomProperty,
        };
    }
}</pre>
</div>