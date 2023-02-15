<p>There are many different types of consolidators you can create. The process to create and update the consolidator depends on the input data format, output data format, and the consolidation technique.</p>

<h4>Time Period Consolidators</h4>

<?php echo file_get_contents(DOCS_RESOURCES."/consolidators/consolidator-info/time-period/introduction.html"); ?>

<p>The following table shows which consolidator type to use based on the data format of the input and output:</p>

<table class="qc-table table">
   <thead>
      <tr>
         <th>Input</th>
         <th>Output</th>
         <th>Class Type</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td><code>TradeBar</code></td>
         <td><code>TradeBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/time-period-consolidators#02-Consolidate-Trade-Bars'>TradeBarConsolidator</a></td>
      </tr>
      <tr>
         <td><code>QuoteBar</code></td>
         <td><code>QuoteBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/time-period-consolidators#03-Consolidate-Quote-Bars'>QuoteBarConsolidator</a></td>
      </tr>
      <tr>
         <td><code>Tick</code></td>
         <td><code>TradeBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/time-period-consolidators#04-Consolidate-Trade-Ticks'>TickConsolidator</a></td>
      </tr>
      <tr>
         <td><code>Tick</code></td>
         <td><code>QuoteBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/time-period-consolidators#05-Consolidate-Quote-Ticks'>TickQuoteBarConsolidator</a></td>
      </tr>
   </tbody>
</table>

<h4>Calendar Consolidators</h4>

<?php echo file_get_contents(DOCS_RESOURCES."/consolidators/consolidator-info/calendar/introduction.html"); ?>

<p>The following table shows which consolidator type to use based on the data format of the input and output:</p>

<table class="qc-table table">
   <thead>
      <tr>
         <th>Input</th>
         <th>Output</th>
         <th>Class Type</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td><code>TradeBar</code></td>
         <td><code>TradeBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/calendar-consolidators#02-Consolidate-Trade-Bars'>TradeBarConsolidator</a></td>
      </tr>
      <tr>
         <td><code>QuoteBar</code></td>
         <td><code>QuoteBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/calendar-consolidators#03-Consolidate-Quote-Bars'>QuoteBarConsolidator</a></td>
      </tr>
      <tr>
         <td><code>Tick</code></td>
         <td><code>TradeBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/calendar-consolidators#04-Consolidate-Trade-Ticks'>TickConsolidator</a></td>
      </tr>
      <tr>
         <td><code>Tick</code></td>
         <td><code>QuoteBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/calendar-consolidators#05-Consolidate-Quote-Ticks'>TickQuoteBarConsolidator</a></td>
      </tr>
   </tbody>
</table>


<h4>Count Consolidators</h4>

<?php echo file_get_contents(DOCS_RESOURCES."/consolidators/consolidator-info/count/introduction.html"); ?>

<p>The following table shows which consolidator type to use based on the data format of the input and output:</p>

<table class="qc-table table">
   <thead>
      <tr>
         <th>Input</th>
         <th>Output</th>
         <th>Class Type</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td><code>TradeBar</code></td>
         <td><code>TradeBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/count-consolidators#02-Consolidate-Trade-Bars'>TradeBarConsolidator</a></td>
      </tr>
      <tr>
         <td><code>QuoteBar</code></td>
         <td><code>QuoteBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/count-consolidators#03-Consolidate-Quote-Bars'>QuoteBarConsolidator</a></td>
      </tr>
      <tr>
         <td><code>Tick</code></td>
         <td><code>TradeBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/count-consolidators#04-Consolidate-Trade-Ticks'>TickConsolidator</a></td>
      </tr>
      <tr>
         <td><code>Tick</code></td>
         <td><code>QuoteBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/count-consolidators#05-Consolidate-Quote-Ticks'>TickQuoteBarConsolidator</a></td>
      </tr>
   </tbody>
</table>

<h4>Mixed-Mode Consolidators</h4>

<?php echo file_get_contents(DOCS_RESOURCES."/consolidators/consolidator-info/mixed-mode/introduction.html"); ?>

<p>The following table shows which consolidator type to use based on the data format of the input and output:</p>

<table class="qc-table table">
   <thead>
      <tr>
         <th>Input</th>
         <th>Output</th>
         <th>Class Type</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td><code>TradeBar</code></td>
         <td><code>TradeBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/mixed-mode-consolidators#02-Consolidate-Trade-Bars'>TradeBarConsolidator</a></td>
      </tr>
      <tr>
         <td><code>QuoteBar</code></td>
         <td><code>QuoteBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/mixed-mode-consolidators#03-Consolidate-Quote-Bars'>QuoteBarConsolidator</a></td>
      </tr>
      <tr>
         <td><code>Tick</code></td>
         <td><code>TradeBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/mixed-mode-consolidators#04-Consolidate-Trade-Ticks'>TickConsolidator</a></td>
      </tr>
      <tr>
         <td><code>Tick</code></td>
         <td><code>QuoteBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/mixed-mode-consolidators#05-Consolidate-Quote-Ticks'>TickQuoteBarConsolidator</a></td>
      </tr>
   </tbody>
</table>


<h4>Renko Consolidators</h4>

<?php echo file_get_contents(DOCS_RESOURCES."/consolidators/consolidator-info/renko/introduction.html"); ?>

<p>The following table shows which consolidator type to use based on the data format of the input and output:</p>

<table class="qc-table table">
   <thead>
      <tr>
         <th>Input</th>
         <th>Output</th>
         <th>Class Type</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td><code>TradeBar</code></td>
         <td><code>TradeBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/renko-consolidators/renko-consolidators#02-Consolidate-Trade-Bars'>RenkoConsolidator</a></td>
      </tr>
      <tr>
         <td><code>QuoteBar</code></td>
         <td><code>QuoteBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/renko-consolidators/renko-consolidators#03-Consolidate-Quote-Bars'>RenkoConsolidator</a></td>
      </tr>
      <tr>
         <td><code>Tick</code></td>
         <td><code>TradeBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/renko-consolidators/renko-consolidators#04-Consolidate-Trade-Ticks'>RenkoConsolidator</a></td>
      </tr>
      <tr>
         <td><code>Tick</code></td>
         <td><code>QuoteBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/renko-consolidators/renko-consolidators#05-Consolidate-Quote-Ticks'>RenkoConsolidator</a></td>
      </tr>
   </tbody>
</table>

<h4>Classic Renko Consolidators</h4>

<?php echo file_get_contents(DOCS_RESOURCES."/consolidators/consolidator-info/classic-renko/introduction.html"); ?>

<p>The following table shows which consolidator type to use based on the data format of the input and output:</p>

<table class="qc-table table">
   <thead>
      <tr>
         <th>Input</th>
         <th>Output</th>
         <th>Class Type</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td><code>TradeBar</code></td>
         <td><code>TradeBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/renko-consolidators/classic-renko-consolidators#02-Consolidate-Trade-Bars'>ClassicRenkoConsolidator</a></td>
      </tr>
      <tr>
         <td><code>QuoteBar</code></td>
         <td><code>QuoteBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/renko-consolidators/classic-renko-consolidators#03-Consolidate-Quote-Bars'>ClassicRenkoConsolidator</a></td>
      </tr>
      <tr>
         <td><code>Tick</code></td>
         <td><code>TradeBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/renko-consolidators/classic-renko-consolidators#04-Consolidate-Trade-Ticks'>ClassicRenkoConsolidator</a></td>
      </tr>
      <tr>
         <td><code>Tick</code></td>
         <td><code>QuoteBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/renko-consolidators/classic-renko-consolidators#05-Consolidate-Quote-Ticks'>ClassicRenkoConsolidator</a></td>
      </tr>
   </tbody>
</table>

<h4>Volume Renko Consolidators</h4>
<?php echo file_get_contents(DOCS_RESOURCES."/consolidators/consolidator-info/volume-renko/introduction.html"); ?>

<p>The following table shows which consolidator type to use based on the data format of the input and output:</p>

<table class="qc-table table">
   <thead>
      <tr>
         <th>Input</th>
         <th>Output</th>
         <th>Class Type</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td><code>TradeBar</code></td>
         <td><code>TradeBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/renko-consolidators/volume-renko-consolidators#02-Consolidate-Trade-Bars'>VolumeRenkoConsolidator</a></td>
      </tr>
      <tr>
         <td><code>Tick</code></td>
         <td><code>TradeBar</code></td>
         <td><a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/renko-consolidators/volume-renko-consolidators#04-Consolidate-Trade-Ticks'>VolumeRenkoConsolidator</a></td>
      </tr>
   </tbody>
</table>


<h4>Sequential Consolidators</h4>

<?php echo file_get_contents(DOCS_RESOURCES."/consolidators/sequential-consolidator-intro.html"); ?>

<p>For more information about sequential consolidators, see <a href='/docs/v2/writing-algorithms/consolidating-data/consolidator-types/combining-consolidators'>Combining Consolidators</a>.</p>
