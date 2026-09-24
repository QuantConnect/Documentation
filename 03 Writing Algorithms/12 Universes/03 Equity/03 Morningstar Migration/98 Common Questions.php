<?php
$faqSchema = <<<'JSON'
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {
            "@type": "Question",
            "name": "Does the migration change my live algorithms?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Yes. When your live algorithm uses the new dataset, the values differ from the old ones, so the algorithm can select a different universe."
            }
        },
        {
            "@type": "Question",
            "name": "How do I tell whether a specific property changed?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Find the property in the migration atlas. The atlas shows whether each period window of the property is identical, changed, reduced, lost, or new, and the reason for each change."
            }
        }
    ]
}
JSON;
include(DOCS_RESOURCES."/faq.php");
?>
