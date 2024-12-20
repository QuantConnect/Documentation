<p class='csharp'>To get a DataFrame from a history request, use Python.</p>

<p class='python'>
  The most popular return type is a <code>DataFrame</code>. 
  If you request a <code>DataFrame</code>, LEAN unpacks the data from <code>Slice</code> objects to populate the <code>DataFrame</code>. 
  If you intend to use the data in the <code>DataFrame</code> to create <code>TradeBar</code> or <code>QuoteBar</code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN will waste computational resources populating the <code>DataFrame</code>.
</p>

<p class='python'>
  When your history request returns a <code>DataFrame</code>, the timestamps in the <code>DataFrame</code> are based on the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones#05-Data-Time-Zone'>data time zone</a>.
</p>
