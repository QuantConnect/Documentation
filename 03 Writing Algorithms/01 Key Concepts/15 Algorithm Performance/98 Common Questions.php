<?php
$faqSchema = <<<'JSON'
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {
            "@type": "Question",
            "name": "My node has 4 cores, so why does my algorithm only use 170% CPU?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "That is the expected reading for a Python algorithm. CPython has a Global Interpreter Lock, so only one thread executes Python bytecode at a time. Your algorithm code holds that lock while it runs, which caps it at roughly one core no matter how many the node has. The remaining usage comes from the LEAN subsystems that are written in C#, such as the data feed and the history provider, which do run on several threads. The result lands well under 400% on a 4-core node. Nothing is misconfigured, and a larger node does not raise the number."
            }
        },
        {
            "@type": "Question",
            "name": "Would rewriting the algorithm in C# let it use all the cores?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "No. C# removes the Global Interpreter Lock as a constraint, but your algorithm's events still fire synchronously in backtesting, so one backtest never saturates several cores with strategy logic. C# is also only marginally faster overall, because the engine does the heavy work in C# either way and the language of your algorithm applies to your own code alone. The rewrite pays off when the strategy processes a high rate of events, such as tick resolution or a large universe. See <a href=\"/docs/v2/writing-algorithms/key-concepts/algorithm-performance#06-Language-Choice\">Language Choice</a>."
            }
        },
        {
            "@type": "Question",
            "name": "How do I make one backtest run faster if extra cores do not help?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Reduce the work the algorithm does rather than the hardware it runs on. Measure with the <a href=\"/docs/v2/writing-algorithms/key-concepts/algorithm-performance#02-Measure-First\">Performance chart</a> to find the dominant cost, then cut the <a href=\"/docs/v2/writing-algorithms/key-concepts/algorithm-performance#03-Reduce-Data-Volume\">data volume</a>, remove <a href=\"/docs/v2/writing-algorithms/key-concepts/algorithm-performance#04-History-Requests\">history requests</a> from repeated code paths, and keep the <a href=\"/docs/v2/writing-algorithms/key-concepts/algorithm-performance#05-Thin-Event-Handlers\">event handlers thin</a>. To run more backtests at once, add nodes instead of enlarging them."
            }
        }
    ]
}
JSON;
include(DOCS_RESOURCES."/faq.php");
?>
