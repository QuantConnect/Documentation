<p>Follow these steps to install Local Platform:</p>

<ol>
    <li><a href='<?=$installDockerLink?>'>Install Docker</a>.</li>
    <li>Open a terminal and download the latest LEAN image.</li>
    <? if ($os == "mac") { ?>
    <p>If your Mac has an M1 or M2 chip, follow the steps in <a href='/docs/v2/local-platform/installation/install-on-macos#06-Troubleshooting'>Troubleshooting</a>. Otherwise, run:</p>
    <? } ?>
    <div class="cli section-example-container">
    	<pre>$ docker pull quantconnect/lean</pre>
    </div>
    <p>It takes about an hour to download the image. While it's downloading, continue to the next step. When you use Local Platform, it automatically pulls the latest LEAN image if your current version is more than a week old.</p>
    <li><a rel="nofollow" target="_blank" href="https://code.visualstudio.com/download">Install Visual Studio Code</a>.</li>
    <li><a rel="nofollow" target="_blank" href="https://marketplace.visualstudio.com/items?itemName=quantconnect.quantconnect">Install Local Platform</a>.</li>
</ol>

<p>If you open Visual Studio Code and it asks you to log in to QuantConnect, you successfully installed Local Platform.</p>
<img class="docs-image" src="https://cdn.quantconnect.com/i/tu/initialization-checklist-1.png" alt="Initialization checklist panel">
