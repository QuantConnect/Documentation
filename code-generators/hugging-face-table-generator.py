from requests import get

REPOSITORIES = '''
Repo: ProsusAI/finbert. Revisions ['4556d13015211d73dccd3fdd39d39232506f3e43']
Repo: Salesforce/moirai-1.0-R-base. Revisions ['2149dc1c56c5d2684390ee4ec6fde58be4196c0c']
Repo: Salesforce/moirai-1.0-R-large. Revisions ['2665aa4fcc9edc1402a3ad1243addfe32cd2178f']
Repo: Salesforce/moirai-1.0-R-small. Revisions ['a34614afbe6b16fffbc11c77daba5aab3ed277fb']
Repo: StephanAkkerman/FinTwitBERT-sentiment. Revisions ['da059da3b3bbcb43f9ed1aeb5ae61644010c7e1e']
Repo: ahmedrachid/FinancialBERT-Sentiment-Analysis. Revisions ['656931965473ec085d195680bd62687b140c038f']
Repo: amazon/chronos-t5-base. Revisions ['b6748377ca1c242cb95ed1187b8b3fe46942c023']
Repo: amazon/chronos-t5-large. Revisions ['16dc70e284b7b209340c258c1375dcee93e3a768']
Repo: amazon/chronos-t5-small. Revisions ['476a71b73e6205f7987e811a81f355b9791c9256']
Repo: amazon/chronos-t5-tiny. Revisions ['d968d90a73cc4e3a3103e262d1d895204e74e415']
Repo: autogluon/chronos-t5-base. Revisions ['b6748377ca1c242cb95ed1187b8b3fe46942c023']
Repo: autogluon/chronos-t5-large. Revisions ['16dc70e284b7b209340c258c1375dcee93e3a768']
Repo: autogluon/chronos-t5-tiny. Revisions ['d968d90a73cc4e3a3103e262d1d895204e74e415']
Repo: bardsai/finance-sentiment-fr-base. Revisions ['08571a47b6fadcd9814ea41c43e168523a1e2d64']
Repo: cardiffnlp/twitter-roberta-base-sentiment-latest. Revisions ['4ba3d4463bd152c9e4abd892b50844f30c646708']
Repo: distilbert/distilbert-base-cased-distilled-squad. Revisions ['564e9b582944a57a3e586bbb98fd6f0a4118db7f']
Repo: distilbert/distilbert-base-uncased. Revisions ['12040accade4e8a0f71eabdb258fecc2e7e948be']
Repo: google-bert/bert-base-uncased. Revisions ['86b5e0934494bd15c9632b12f734a8a67f723594']
Repo: google/gemma-7b. Revisions ['ff6768d9368919a1f025a54f9f5aa0ee591730bb', 'a0eac5b80dba224e6ed79d306df50b1e92c2125d']
Repo: microsoft/deberta-base. Revisions ['0d1b43ccf21b5acd9f4e5f7b077fa698f05cf195']
Repo: mrm8488/distilroberta-finetuned-financial-news-sentiment-analysis. Revisions ['ae0eab9ad336d7d548e0efe394b07c04bcaf6e91']
Repo: nickmuchi/deberta-v3-base-finetuned-finance-text-classification. Revisions ['e07986b01cb87923b2e1622356f8093e173ee9a8']
Repo: nickmuchi/distilroberta-finetuned-financial-text-classification. Revisions ['396d9c2c093f87875c3fdfa03ad7eed792e776e9']
Repo: nickmuchi/sec-bert-finetuned-finance-classification. Revisions ['15cae24ba4089500a7e18f340e0286160b1daf14']
Repo: openai-community/gpt2. Revisions ['607a30d783dfa663caf39e06633721c8d4cfcd7e']
Repo: yiyanghkust/finbert-tone. Revisions ['4921590d3c0c3832c0efea24c8381ce0bda7844b']
'''

EXAMPLES = {
    'chronos-t5' : '/docs/v2/writing-algorithms/machine-learning/hugging-face/popular-models/chronos-t5',
    'finbert': '/docs/v2/writing-algorithms/machine-learning/hugging-face/popular-models/finbert'
}

def __to_row(line):
    url = line.split('Revisions')[0][6:-2]
    category = __get_category(url)
    example = __get_example(url)
    return f'<tr><td><a rel="nofollow" target="_blank" href="https://huggingface.co/{url}">{url}</a></td><td>{category}</td><td style="text-align:center;">{example}</td></tr>'

def __get_category(url):
    content = get(f"https://huggingface.co/{url}").text
    start = content.find('<span>', content.find('models?pipeline_tag=')) + 6
    return content[start:content.find('</span>', start)]

def __get_example(url):
    example = next(filter(lambda x: x[0] in url, EXAMPLES.items()), None)
    return f'<a href="{example[1]}"><i class="fa fa-external-link"></i></a>' if example else ''

if __name__ == '__main__':
    rows = sorted([__to_row(x) for x in REPOSITORIES.split('\n') if x], key=lambda x: x.lower())
    with open("Resources/machine-learning/hugging-face-table.html", mode='w') as f:
        rows = '\n        '.join(rows)
        f.write(f'''<table class="qc-table table">
    <thead><tr><th>Name</th><th>Category</th><th style="text-align:center;">Example</th></tr></thead>
    <tbody>
        {rows}
    </tbody>
</table>
''')
