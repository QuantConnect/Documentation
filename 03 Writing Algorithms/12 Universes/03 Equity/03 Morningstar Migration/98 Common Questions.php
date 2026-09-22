<?php
$faqSchema = <<<'JSON'
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {
            "@type": "Question",
            "name": "Can I keep backtesting on the old Morningstar data?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "No. Morningstar retired the feeds that produced it, so the old data can no longer be corrected or extended, and QuantConnect serves one fundamental history. Backtests that ran on it before September 23, 2026 cannot be reproduced."
            }
        },
        {
            "@type": "Question",
            "name": "Why did my backtest return drop after the migration?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "The most common cause is the removal of a look-ahead. The old feeds dated ratios, growth, per-share, and average properties at the end of the reporting period, weeks before the filing that produced them was public. Those figures now arrive a median of 63 days later, so a strategy that traded on them earned less than the old backtest showed. Live trading never received the early values."
            }
        },
        {
            "@type": "Question",
            "name": "Do I have to change my algorithm's code?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Only if it reads a property Morningstar retired. Those members still compile but throw a <code>NotSupportedException</code> that names the replacement, so compile the project and run it over a short period to find them. Everything else keeps working and returns the new values."
            }
        },
        {
            "@type": "Question",
            "name": "Does the migration change my live algorithms?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Live algorithms receive the new feeds from September 23, 2026. The values differ from the old ones, so a live algorithm can select a different universe than it did the day before, and a retired property throws. The upside is that research, backtesting, and live trading now agree on both the values and the dates they carry."
            }
        },
        {
            "@type": "Question",
            "name": "How do I tell whether a specific property changed?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Request the history of the property over a period you already studied and compare it against the figures in your old backtest logs. Pay attention to the dates as well as the values: a property in the ratio, growth, per-share, or average families can carry the same figure on a later date."
            }
        }
    ]
}
JSON;
include(DOCS_RESOURCES."/faq.php");
?>
