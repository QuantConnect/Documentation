<? include(DOCS_RESOURCES."/landing-page-introductions/individual-brokerages.php"); ?>
<? include(DOCS_RESOURCES."/brokerages/introduction-by-brokerage/tastytrade.html"); ?>
<p>To view the implementation of the tastytrade brokerage integration, see the <a href='https://github.com/QuantConnect/Lean.Brokerages.tastytrade' rel='nofollow' target="_blank">tastytrade repository</a>.</p>
<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function() {
    const headings = document.querySelectorAll('h2.page-sub-heading > span');
    headings.forEach(span => {
        if (span.textContent.trim() === "Tastytrade") {
            span.textContent = "tastytrade";
        }
    });
});
</script>
