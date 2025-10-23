<div class='section-example-container'>
<pre class='csharp'>
public class AddSecurityInitializerExampleAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        // In the Initialize method, set the security initializer to set models of assets.
        AddSecurityInitializer(CustomSecurityInitializer);
    }

    private void CustomSecurityInitializer(Security security)
    {
        // Overwrite <?=$comment ?? "some of the reality models"?>

        <?=$overwriteCodeC?>
    }
}</pre>
<pre class='python'>class AddSecurityInitializerExampleAlgorithm(QCAlgorithm):
    def initialize(self) -&gt; None:
        # In the Initialize method, set the security initializer to set models of assets.
        self.add_security_initializer(self._custom_security_initializer)

    def _custom_security_initializer(self, security: Security) -&gt; None:
        # Overwrite <?=$comment ?? "some of the reality models"?>
        
        <?=$overwriteCodePy?></pre>
</div>