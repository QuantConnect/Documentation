<p>
    Warm-up simulates winding back the clock from the time you deploy the algorithm. 
    In a backest, this is the <a href='/docs/v2/writing-algorithms/initialization#02-Set-Dates'>start date</a> of your algorithm. 
    In live trading, it's the current date.    
    Follow these steps to add a warm-up period to the start of your algorithm:
</p>

<ol>
    <li>Create a new project.</li>
    <p>The process to create a new project depends on if you use the <a href='/docs/v2/cloud-platform/projects/getting-started#03-Create-Projects'>Cloud Platform</a>, <a href='/docs/v2/local-platform/projects/getting-started#03-Create-Projects'>Local Platform</a>, or <a href='/docs/v2/lean-cli/projects/project-management#02-Create-Projects'>CLI</a>.</p>

    <li>In the <code class='python'>initialize</code><code class='csharp'>Initialize</code> method, call the <code class='python'>set_warm_up</code><code class='csharp'>SetWarmUp</code> method with the warm-up duration.</li>
    <div class="section-example-container">
        <pre class="csharp">SetWarmUp(10, Resolution.Daily);</pre>
        <pre class="python">self.set_warm_up(10, Resolution.DAILY)</pre>
    </div>
</ol>
