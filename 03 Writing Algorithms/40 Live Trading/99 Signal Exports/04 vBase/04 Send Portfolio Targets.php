<p>
    To send targets, pass a list of <code>PortfolioTarget</code> objects to the 
    <code class="csharp">SetTargetPortfolio</code><code class="python">set_target_portfolio</code> method. 
    The method returns a boolean value indicating whether the targets were successfully sent to vBase.
</p>

<p>
    <strong>What gets stamped?</strong> The provider builds a <code>sym,wt</code> (symbol-weight) CSV and, under the hood, 
    uses <code>PortfolioTarget.Percent</code> to convert your absolute target quantities into portfolio weights. 
    This ensures the stamp reflects the weighted sizing of your positions at the time of export.
    Default behavior can be customized by overriding the <code>BuildCsv</code> method of the <code>VBaseSignalExport</code> class.
</p>

<div class="section-example-container">
<pre class="csharp">var success = SignalExport.SetTargetPortfolio(targets);</pre>
<pre class="python">success = self.signal_export.set_target_portfolio(targets)</pre>
</div>