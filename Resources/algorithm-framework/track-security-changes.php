<p>The <a href='/docs/v2/writing-algorithms/algorithm-framework/universe-selection/key-concepts'>Universe Selection model</a> may select a dynamic universe of assets, so you should not assume a fixed set of assets in the <?=$modelName?> model. When the Universe Selection model adds and removes assets from the universe, it triggers an <code>OnSecuritiesChanged</code> event. In the <code>OnSecuritiesChanged</code> event handler, you can initialize the security-specific state or load any history required for your <?=$modelName?> model. If you need to save data for individual securities, <span class='python'>add custom members to the respective <code>Security</code> object</span><span class='csharp'>cast the <code>Security</code> object to a <code>dynamic</code> object and then save custom members to it</span>.</p>

<div class="section-example-container">
    <pre class="csharp">class My<?=$modelClassName?> : <?=$modelClassName?>
{
    private List&lt;Security&gt; _securities = new List&lt;Security&gt;();

    public override void OnSecuritiesChanged(QCAlgorithm algorithm, SecurityChanges changes)
    {
<? if ($callsBaseClass) { ?>
        base.OnSecuritiesChanged(algorithm, changes);
<? } ?>
        foreach (var security in changes.AddedSecurities)
        {               
            // Store and manage Symbol-specific data
            var dynamicSecurity = security as dynamic;
            dynamicSecurity.Sma = SMA(security.Symbol, 20);

            _securities.Add(security);
        }

        foreach (var security in changes.RemovedSecurities)
        {
            if (_securities.Contains(security))
            {
                algorithm.DeregisterIndicator((security as dynamic).Sma);

                _securities.Remove(security);
            }
        }
    }
}</pre>
    <pre class="python">class My<?=$modelClassName?>(<?=$modelClassName?>):
    securities = []

    def OnSecuritiesChanged(self, algorithm: QCAlgorithm, changes: SecurityChanges) -&gt; None:
<? if ($callsBaseClass) { ?>
        super().OnSecuritiesChanged(algorithm, changes)
<? } ?>
        for security in changes.AddedSecurities:
            # Store and manage Symbol-specific data
            security.indicator = algorithm.SMA(security.Symbol, 20)
            algorithm.WarmUpIndicator(security.Symbol, security.indicator)

            self.securities.append(security)

        for security in changes.RemovedSecurities:
            if security in self.securities:
                algorithm.DeregisterIndicator(security.indicator)
                self.securities.remove(security)</pre>
</div>
