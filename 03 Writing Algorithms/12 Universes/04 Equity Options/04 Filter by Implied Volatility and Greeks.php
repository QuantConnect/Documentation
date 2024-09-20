<p>
  Option price models compute the theoretical price of Option contracts, their implied volatility, and their Greek values. Theoretical prices can help you detect undervalued and overvalued contracts, implied volatility can provide you insight into the upcoming volatility of the underlying security, and Greek values can help you hedge your portfolio. The following table describes the filter methods of the <code>OptionFilterUniverse</code> class that select contract for a range of implied volatility, Greek values, and open interest.
</p>

<?
  $assetClass = "Option";
  include(DOCS_RESOURCES."/universes/option/option-filter-universe-pricing.html"); 
  include(DOCS_RESOURCES."/universes/option/filter-examples-pricing.html");
?>
