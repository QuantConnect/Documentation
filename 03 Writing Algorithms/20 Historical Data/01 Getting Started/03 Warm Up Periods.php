<p>
    Warm-up simulates winding back the clock from the time you deploy the algorithm. 
    In a backest, this is the <a href='/docs/v2/writing-algorithms/initialization#02-Set-Dates'>start date</a> of your algorithm. 
    In live trading, it's the current date.    
    Follow these steps to add a warm-up period to the start of your algorithm:
</p>

<ol>
    <li>Create a new project.</li>
    <p>The process to create a new project depends on if you use the <a href='/docs/v2/cloud-platform/projects/getting-started#03-Create-Projects'>Cloud Platform</a>, <a href='/docs/v2/local-platform/projects/getting-started#03-Create-Projects'>Local Platform</a>, or <a href='/docs/v2/lean-cli/projects/project-management#02-Create-Projects'>CLI</a>.</p>

    <li>In the <code class='python'>initialize</code><code class='csharp'>Initialize</code> method, <a href='/docs/v2/writing-algorithms/initialization#02-Set-Dates'>set the backtest dates</a> and <a href='/docs/v2/writing-algorithms/securities/requesting-data#02-Add-Assets'>add an asset</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">SetStartDate(2024, 12, 1);
SetEndDate(2024, 12, 2);
AddEquity("SPY", Resolution.Daily);</pre>
        <pre class="python">self.set_start_date(2024, 12, 1)
self.set_end_date(2024, 12, 2)
self.add_equity("SPY", Resolution.DAILY)</pre>
    </div>
    
    <li>In the <code class='python'>initialize</code><code class='csharp'>Initialize</code> method, call the <code class='python'>set_warm_up</code><code class='csharp'>SetWarmUp</code> method with the warm-up duration.</li>
    <div class="section-example-container">
        <pre class="csharp">SetWarmUp(10, Resolution.Daily);</pre>
        <pre class="python">self.set_warm_up(10, Resolution.DAILY)</pre>
    </div>

    <li>In the <code class='python'>on_data</code><code class='csharp'>OnData</code> method, <a href='/docs/v2/writing-algorithms/logging'>log</a> the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>time</a> and <a href='/docs/v2/writing-algorithms/historical-data/warm-up-periods#07-Warm-Up-vs-History-Requests'>warm-up state</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">Log($"IsWarmingUp at {Time}: {IsWarmingUp}");</pre>
        <pre class="python">self.log(f"self.is_warming_up at {self.time}: {self.is_warming_up}")</pre>
    </div>

    <div class="csharp section-example-container"><pre>
Algorithm starting warm up...
IsWarmingUp at 11/22/2024 4:00:00 PM: True
IsWarmingUp at 11/25/2024 4:00:00 PM: True
IsWarmingUp at 11/26/2024 4:00:00 PM: True
IsWarmingUp at 11/27/2024 4:00:00 PM: True
IsWarmingUp at 11/29/2024 1:00:00 PM: True
Algorithm finished warming up.
IsWarmingUp at 12/2/2024 4:00:00 PM: False</pre></div>
    <div class="python section-example-container"><pre>
Algorithm starting warm up...
self.is_warming_up at 2024-11-22 16:00:00: True
self.is_warming_up at 2024-11-25 16:00:00: True
self.is_warming_up at 2024-11-26 16:00:00: True
self.is_warming_up at 2024-11-27 16:00:00: True
self.is_warming_up at 2024-11-29 13:00:00: True
Algorithm finished warming up.
self.is_warming_up at 2024-12-02 16:00:00: False</pre></div>
</ol>

<p>For more information about warm-up, see <a href='/docs/v2/writing-algorithms/historical-data/warm-up-periods'>Warm Up Periods</a>.</p>
