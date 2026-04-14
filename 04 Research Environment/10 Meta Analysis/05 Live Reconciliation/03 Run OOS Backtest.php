<p>Follow these steps to run an out-of-sample backtest that mirrors the live deployment.</p>

<ol>
    <li>In the project's main algorithm file, set the start date and starting cash to match the values you read in the previous step. Use <code class="csharp">SetStartDate</code><code class="python">set_start_date</code> and <code class="csharp">SetCash</code><code class="python">set_cash</code> so the backtest begins at the same moment and with the same equity as the live deployment. You can either hard-code the values or expose them as <a href='/docs/v2/writing-algorithms/parameter-and-optimization/parameters'>parameters</a>.</li>

    <li>Compile the project by calling the <code class="csharp">CreateCompile</code><code class="python">create_compile</code> method, then poll <code class="csharp">ReadCompile</code><code class="python">read_compile</code> until the compile state is <code>BuildSuccess</code>.</li>
    <div class="section-example-container">
        <pre class="python">from time import sleep

compilation = api.create_compile(project_id)
compile_id = compilation.compile_id

# Poll until the build succeeds.
for attempt in range(10):
    result = api.read_compile(project_id, compile_id)
    if result.state == 'BuildSuccess':
        break
    if result.state == 'BuildError':
        raise Exception(f"Compilation failed: {result.logs}")
    print(f"Compile in queue... (attempt {attempt + 1}/10)")
    sleep(5)</pre>
    </div>

    <li>Create the OOS backtest with the <code class="csharp">CreateBacktest</code><code class="python">create_backtest</code> method.</li>
    <div class="section-example-container">
        <pre class="python">backtest = api.create_backtest(project_id, compile_id, 'OOS Reconciliation')
backtest_id = backtest.backtest_id
print(f"Backtest Id: {backtest_id}")</pre>
    </div>

    <li>Poll the <code class="csharp">ReadBacktest</code><code class="python">read_backtest</code> method until the <code>completed</code> flag is <code>True</code>. Log the <code>progress</code> attribute on each poll so you can watch the backtest advance.</li>
    <div class="section-example-container">
        <pre class="python">completed = False
while not completed:
    result = api.read_backtest(project_id, backtest_id)
    completed = result.completed
    print(f"Backtest running... {result.progress:.2%}")
    sleep(10)
print("Backtest completed.")</pre>
    </div>
</ol>
