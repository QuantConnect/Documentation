<li>Define the <a href='/docs/v2/lean-engine/contributions/datasets/key-concepts#06-TimeZones'>DataTimeZone</a> method.</li>
<div class="section-example-container">
    <pre>public class VendorNameDatasetName<?=$classNameEnding?> : BaseData
{
    public override DateTimeZone DataTimeZone()
    {
        return DateTimeZone.Utc;
    }
}</pre>
</div>

<p>If you import <code>using QuantConnect</code>, the <code>TimeZones</code> class provides helper attributes to create <code>DateTimeZone</code> objects. For example, you can use <code>TimeZones.Utc</code> or <code>TimeZones.NewYork</code>. For more information about time zones, see <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones'>Time Zones</a>.</p>

<li>Define the <code>SupportedResolutions</code> method.</li>
<div class="section-example-container">
    <pre>public class VendorNameDatasetName<?=$classNameEnding?> : BaseData
{
    public override List&lt;Resolution&gt; SupportedResolutions()
    {
        return DailyResolution;
    }
}</pre>
</div>

<?php if ($classNameEnding == "Universe") { ?><p>Universe data must have hour or daily resolution.</p><?php } ?>

<p>The <code>Resolution</code> enumeration has the following members:</p>

<div data-tree="QuantConnect.Resolution"></div>


<li>Define the <code>DefaultResolution</code> method.</li>

<p>If a member doesn't specify a resolution when they subscribe to your dataset, Lean uses the <code>DefaultResolution</code>.</p>

<div class="section-example-container">
    <pre>public class VendorNameDatasetName<?=$classNameEnding?> : BaseData
{
    public override Resolution DefaultResolution()
    {
        return Resolution.Daily;
    }
}</pre>
</div>

<li>Define the <code>IsSparseData</code> method.</li>

<p>If your dataset is not tick resolution and your dataset is missing data for at least one sample, it's sparse. If your dataset is sparse, we disable logging for missing files.</p>
<div class="section-example-container">
    <pre>public class VendorNameDatasetName<?=$classNameEnding?> : BaseData
{
    public override bool IsSparseData()
    {
        return true;
    }
}</pre>
</div>

<li>Define the <a href='/docs/v2/lean-engine/contributions/datasets/key-concepts#08-Linked-Datasets'>RequiresMapping</a> method.</li>
<div class="section-example-container">
    <pre>public class VendorNameDatasetName<?=$classNameEnding?> : BaseData
{
    public override bool RequiresMapping()
    {
        return true;
    }
}</pre>
</div>

<li>Define the <code>Clone</code> method.</li>
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

<li>Define the <code>ToString</code> method.</li>
<div class="section-example-container">
    <pre>public class VendorNameDatasetName<?=$classNameEnding?> : BaseData
{
    public override string ToString()
    {
        return $"{Symbol} - {SomeCustomProperty}";
    }
}</pre>
</div>
