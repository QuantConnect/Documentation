<!-- <p>Datasets are used in your trading to inform decisions and fulfill trades. Datasets include market data and alternative data. The market and alternative data are sourced from data providers.</p> -->-TickType values<div>&nbsp;&nbsp;&nbsp; - Trade, Quote</div><div>-In backtesting, ticks are grouped together into milisecond buckets</div><div>-In live trading, ticks are streamed directly to your algorithm as soon as they occur<br></div><div></div>

<div>-Tick trade Definition: a record of a transaction or sale for the security</div><div>-Contains Quantity and Price values</div><div>-Useful for HFT strategies<br></div><div></div>


<div>-Tick quote definition: a bid or offer to purchase the security for a specific price</div><div>-Quote ticks contain non-zero BidPrice, BidSize, AskPrice, and AskSize properties</div><div></div>

<div>-Tick data is raw and unfiltered</div><div>-may contain bad ticks which skew your trade results</div><div>-Some ticks come from dark pools, not tradable</div><div>-We recommend only using tick data if you understand the risks and are able to perform your own online tick filtering.</div><div>-Add image of tick going outside of bid/ask and tick crashing far outside normal price (see image from Jared)</div><div>-Marked by QC using a model (? - add a link to the source code)<br></div><div></div>

<br>## Copy from US Equity