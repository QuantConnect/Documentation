
<p>Quit statements cause your project to stop running and may log some data to the log file and Cloud Terminal. These statements are orange in the Cloud Terminal. When you call the <code>Quit</code> method, the program continues executing until the end of the method definition. If you want to quit execution immediately, <code>return</code> after you call <code>Quit</code>.<br></p>

<div class="section-example-container">
    <pre class="csharp">Quit("My quit message");</pre>
    <pre class="python">self.quit("My quit message")</pre>
</div>
