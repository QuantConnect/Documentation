<? if ($isCLIDocs) { ?>
    <p>
        The Lean CLI is distributed as a Python package, so it requires <code>pip</code> to be installed.
        Because <code>pip</code> is distributed as a part of Python, you must install Python before you can install the CLI.
    </p>
<? } else { ?>
    <p>
        QuantConnect Local requires <code>pip</code> to be installed.
        Because <code>pip</code> is distributed as a part of Python, you must install Python before you can install the QuantConnect Local extension.
    </p>
<? } ?>

<p>
    This page contains installation instructions for <a href="https://docs.anaconda.com/anaconda/" target="_blank">Anaconda</a>, which is a Python distribution containing a lot of packages that are also available when running the LEAN engine.
    Having these packages installed locally makes it possible for your editor to provide autocomplete for them.
</p>

<p>
    Note that the Python distribution from the Microsoft Store is not supported. <a href='https://github.com/QuantConnect/lean-cli/issues/54#issuecomment-1104148972' rel='nofollow' target='nofollow'>Docker doesn't support Python 3.10</a>, so you must have 3.9.x or lower. 
</p>