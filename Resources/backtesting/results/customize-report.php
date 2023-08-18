<h4>Customize the Report HTML</h4>

<p>The <a rel='nofollow' target='_blank' href='https://github.com/QuantConnect/Lean/blob/master/Report/template.html'><span class='public-file-name'>Report / template.html</span></a> in the LEAN GitHub repository defines the stucture of the reports you generate. To override the HTML file, <?=$addHTMLFileInstructions?>. To include some of the information and charts that are in the default report, use the report keys in the <a rel='nofollow' target='_blank' href='https://github.com/QuantConnect/Lean/blob/master/Report/ReportKey.cs'><span class='public-file-name'>Report / ReportKey.cs</span></a> file in the LEAN GitHub repository. For example, to add the <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/glossary#27-Sharpe-ratio'>Sharpe ratio</a> of your backtest to the custom HTML file, use <code>{{$KPI-SHARPE}}</code>.</p>

<p>To include the <a href='/docs/v2/cloud-platform/backtesting/report#08-Crisis-Events'>crisis event plots</a> in your report, add the <code>{{$HTML-CRISIS-PLOTS}}</code> key and then define the structure of the individual plots inside of <code>&lt;!--crisis</code> and <code>crisis--&gt;</code>. Inside of this comment, you can utilize the <code>{{$TEXT-CRISIS-TITLE}}</code> and <code>{{$PLOT-CRISIS-CONTENT}}</code> keys. For example, the following HTML is the default format for each crisis plot:</p>

<div class="section-example-container">
    <pre class="html">&lt;!--crisis
&lt;div class="col-xs-4"&gt;
    &lt;table class="crisis-chart table compact"&gt;
        &lt;thead&gt;
        &lt;tr&gt;
            &lt;th style="display: block; height: 75px;"&gt;{{$TEXT-CRISIS-TITLE}}&lt;/th&gt;
        &lt;/tr&gt;
        &lt;/thead&gt;
        &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td style="padding:0;"&gt;
                &lt;img src="{{$PLOT-CRISIS-CONTENT}}"&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
        &lt;/tbody&gt;
    &lt;/table&gt;
&lt;/div&gt;
crisis--&gt</pre>
</div>

<p>To include the <a href='https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/report#09-Parameters'>algorithm parameter</a> in your report, add the <code>{{$PARAMETERS}}</code> key and then define the HTML element inside of <code>&lt;!--parameters</code> and <code>parameters--&gt;</code>. Inside of this comment, you can use special keys <code>{{$KEY&lt;parameterIndex&gt;}}</code> and <code>{{$VALUE&lt;parameterIndex&gt;}}</code>, which represent the key and value of a single parameter. For example, the following HTML is the default format for the parameters element:</p>

<div class="section-example-container">
    <pre class="html">&lt;!--parameters
&lt;tr&gt;
    &lt;td class = "title"&gt; {{$KEY0}} &lt;/td&gt;&lt;td&gt; {{$VALUE0}} &lt;/td&gt;
    &lt;td class = "title"&gt; {{$KEY1}} &lt;/td&gt;&lt;td&gt; {{$VALUE1}} &lt;/td&gt;
&lt;/tr&gt;
parameters--&gt</pre>
</div>

<p>In the preceding example, <code>{{$KEY0}}</code> is the name of the first parameter in the algorithm and <code>{{$VALUE0}}</code> is its value.</p>

<? if ($leanCLI) { ?>
<p>To generate the report with your custom HTML file, run <code>lean report --html &lt;pathToCustomHTMLFile&gt;</code>.</p>
<? } ?>

<h4>Customize the Report CSS</h4>

<p>The <a rel='nofollow' target='_blank' href='https://github.com/QuantConnect/Lean/blob/master/Report/css/report.css'><span class='public-file-name'>Report / css / report.css</span></a> in the LEAN GitHub repository defines the style of the reports you generate. To override the stylesheet, <?=$addCSSFileInstructions?>.</p>

<? if ($leanCLI) { ?>
<p>To generate the report with your custom CSS file, run <code>lean report --css &lt;pathToCustomCSSFile&gt;</code>.</p>
<? } ?>
