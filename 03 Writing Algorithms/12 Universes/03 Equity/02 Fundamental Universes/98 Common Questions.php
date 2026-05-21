<?php
$faqSchema = <<<'JSON'
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {
            "@type": "Question",
            "name": "How often does a fundamental universe run?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Fundamental universes run once per day by default. To change the cadence, set <code class=\"csharp\">UniverseSettings.Schedule</code><code class=\"python\">universe_settings.schedule</code> before adding the universe. See <a href=\"/docs/v2/writing-algorithms/universes/settings#10-Schedule\">Schedule</a>."
            }
        },
        {
            "@type": "Question",
            "name": "Why do some Fundamental properties return NaN?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Not every company reports every Morningstar field, so unreported values come back as NaN. Filter the <code>Fundamental</code> objects to exclude NaN values for the property you use before you sort or rank by it."
            }
        },
        {
            "@type": "Question",
            "name": "Does the fundamental universe include delisted companies?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Yes. The <a href=\"/datasets/morning-star-us-fundamentals\">Morningstar US Fundamentals</a> dataset includes delisted tickers so your backtests are free of survivorship bias. The universe excludes ETFs, ADRs, and OTC securities."
            }
        },
        {
            "@type": "Question",
            "name": "Can I call AddEquity inside the universe filter function?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "No. Return a list of <code>Symbol</code> objects from the filter function and LEAN subscribes to them automatically. Calling <code class=\"csharp\">AddEquity</code><code class=\"python\">add_equity</code> inside the filter creates duplicate subscriptions and unexpected behavior."
            }
        },
        {
            "@type": "Question",
            "name": "Can I combine technical indicators with fundamental selection?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Yes. Keep a per-symbol <code>SelectionData</code> helper that updates a <a href=\"/docs/v2/writing-algorithms/indicators/manual-indicators\">manual indicator</a> from the daily price and volume on the <code>Fundamental</code> object, then filter or rank by the indicator value. You cannot attach custom attributes to <code>Fundamental</code> objects, so use a separate dictionary keyed by <code>Symbol</code>. For more information, see <a href=\"/docs/v2/writing-algorithms/indicators/indicator-universes\">Indicator Universes</a>."
            }
        },
        {
            "@type": "Question",
            "name": "Why is my algorithm running out of memory with a fundamental universe?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Each asset in the universe consumes about 5 MB of RAM, so selecting thousands of stocks quickly exhausts the node memory. Tighten the filter so it returns only the assets you trade, and check the RAM capacity of your <a href=\"/docs/v2/cloud-platform/organizations/resources#02-Backtesting-Nodes\">backtesting</a> and <a href=\"/docs/v2/cloud-platform/organizations/resources#04-Live-Trading-Nodes\">live trading nodes</a>."
            }
        }
    ]
}
JSON;
include(DOCS_RESOURCES."/faq.php");
?>