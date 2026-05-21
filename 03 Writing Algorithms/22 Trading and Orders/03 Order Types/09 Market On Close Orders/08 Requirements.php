<p><?php echo file_get_contents(DOCS_RESOURCES."/order-types/moc-buffer.html"); ?>
	
<p>Markets that operate 24/7 don't support MOC orders. The Forex market doesn't operate during the weekend.</p>

<p>MOC orders don't support the <code class="csharp">GoodTilDate</code><code class="python">good_til_date</code> <a href='/docs/v2/writing-algorithms/trading-and-orders/order-properties#03-Time-In-Force'>time in force</a>. If you submit a MOC order with the <code class="csharp">GoodTilDate</code><code class="python">good_til_date</code> time in force, LEAN automatically adjusts the time in force to be <code class="csharp">GoodTilCanceled</code><code class="python">GOOD_TIL_CANCELED</code>.</p>