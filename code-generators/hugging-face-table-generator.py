from requests import get

REPOSITORIES = '''Repo: AutonLab/MOMENT-1-large. Revisions ['4e13083190ff6072880878f307b0bae62967aecc', '0cdac06bc909ce4cdf0a1b6ab622b9df5075e989', 'ca58581bc7bea2ebed4e80dc0a3e4b8b609c6ecc']
Repo: FacebookAI/roberta-base. Revisions ['e2da8e2f811d1448a5b465c236feacd80ffbac7b']
Repo: ProsusAI/finbert. Revisions ['4556d13015211d73dccd3fdd39d39232506f3e43']
Repo: Salesforce/moirai-1.0-R-base. Revisions ['2149dc1c56c5d2684390ee4ec6fde58be4196c0c', '4fa939a8800d9da346c0280f3d9aeba0d2d35877', '8ccee33f01eaafef96169c6ebc5204341d21bf7e']
Repo: Salesforce/moirai-1.0-R-large. Revisions ['b6b84e3fbeb9e6927d4271d625441fb51ab6bbd8', '13a8633b35085404adb94fe9f9bcad486404cdb5', '2665aa4fcc9edc1402a3ad1243addfe32cd2178f']
Repo: Salesforce/moirai-1.0-R-small. Revisions ['f5bd5d01f0d67de856107d43abdd637380aae0a3', 'a34614afbe6b16fffbc11c77daba5aab3ed277fb', '79ae2f2a22a2a0424c53d7c50ed90afc960c2324']
Repo: StephanAkkerman/FinTwitBERT-sentiment. Revisions ['da059da3b3bbcb43f9ed1aeb5ae61644010c7e1e']
Repo: ahmedrachid/FinancialBERT-Sentiment-Analysis. Revisions ['656931965473ec085d195680bd62687b140c038f']
Repo: amazon/chronos-t5-base. Revisions ['a07f71925a2e12c60e874bb9370b0282b174c7a3', 'b6748377ca1c242cb95ed1187b8b3fe46942c023', 'dc471fda89511eea93953a99d6080bbc01134339']
Repo: amazon/chronos-t5-large. Revisions ['16dc70e284b7b209340c258c1375dcee93e3a768', 'defc1f2443ade06c64aec8cd75b732239b0a28ef', 'f3d1cf9d3e01146d8a7849b026b71f6be1bc1aee']
Repo: amazon/chronos-t5-small. Revisions ['4b88b0c1e292508b65cccc806c85b0afef969977', '476a71b73e6205f7987e811a81f355b9791c9256', '6bcab32367f856115ca07bdd7f4956eced00a4b5']
Repo: amazon/chronos-t5-tiny. Revisions ['c615a9cd3300c39179ca761e9f9768b0f6bd1823', 'd968d90a73cc4e3a3103e262d1d895204e74e415', '5a41e2955cdb6388412de8ebec9b8e0e9c7e52b4']
Repo: autogluon/chronos-bolt-base. Revisions ['7354124e97486213d33dae999b1914c5aff19089']
Repo: autogluon/chronos-t5-base. Revisions ['3e03c837f07d8e52cec545b243e4c6fd5f5f4e80', 'b6748377ca1c242cb95ed1187b8b3fe46942c023']
Repo: autogluon/chronos-t5-large. Revisions ['16dc70e284b7b209340c258c1375dcee93e3a768', '406c9dd8bfefe042463b679b242baf585c59018d']
Repo: autogluon/chronos-t5-tiny. Revisions ['86d9dea94199a9fab6abf8114a962d03aac8c3c5', 'd968d90a73cc4e3a3103e262d1d895204e74e415']
Repo: bardsai/finance-sentiment-fr-base. Revisions ['08571a47b6fadcd9814ea41c43e168523a1e2d64']
Repo: cardiffnlp/twitter-roberta-base-sentiment-latest. Revisions ['4ba3d4463bd152c9e4abd892b50844f30c646708']
Repo: deepseek-ai/DeepSeek-R1-Distill-Llama-70B. Revisions ['b1c0b44b4369b597ad119a196caf79a9c40e141e']
Repo: distilbert/distilbert-base-cased-distilled-squad. Revisions ['564e9b582944a57a3e586bbb98fd6f0a4118db7f']
Repo: distilbert/distilbert-base-uncased. Revisions ['12040accade4e8a0f71eabdb258fecc2e7e948be']
Repo: google-bert/bert-base-uncased. Revisions ['86b5e0934494bd15c9632b12f734a8a67f723594']
Repo: google/gemma-7b. Revisions ['ff6768d9368919a1f025a54f9f5aa0ee591730bb', 'a0eac5b80dba224e6ed79d306df50b1e92c2125d']
Repo: ibm-granite/granite-timeseries-ttm-r1. Revisions ['55a115da7eb15664eddd34c52523f51b304f9888', 'f04bebdd4c13475b006ce72672e74c9dc28871dc', 'dbcc76b539ff82c4802462e9e2da5a6f265c8a8d', '2ac89ed42f10643b61219a2c4d39c7145c171ddb']
Repo: microsoft/deberta-base. Revisions ['0d1b43ccf21b5acd9f4e5f7b077fa698f05cf195']
Repo: mrm8488/distilroberta-finetuned-financial-news-sentiment-analysis. Revisions ['ae0eab9ad336d7d548e0efe394b07c04bcaf6e91']
Repo: nickmuchi/deberta-v3-base-finetuned-finance-text-classification. Revisions ['e07986b01cb87923b2e1622356f8093e173ee9a8']
Repo: nickmuchi/distilroberta-finetuned-financial-text-classification. Revisions ['396d9c2c093f87875c3fdfa03ad7eed792e776e9']
Repo: nickmuchi/sec-bert-finetuned-finance-classification. Revisions ['15cae24ba4089500a7e18f340e0286160b1daf14']
Repo: openai-community/gpt2. Revisions ['607a30d783dfa663caf39e06633721c8d4cfcd7e']
Repo: yiyanghkust/finbert-tone. Revisions ['4921590d3c0c3832c0efea24c8381ce0bda7844b']'''

EXAMPLES = {
    'chronos-t5' : '/docs/v2/writing-algorithms/machine-learning/hugging-face/popular-models/chronos-t5',
    'distilbert-base-cased-distilled-squad' : '/docs/v2/writing-algorithms/machine-learning/hugging-face/popular-models/distilbert',
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
    return f'<a href="{example[1]}"><img src="https://cdn.quantconnect.com/i/tu/internal-link.svg" alt="Example"></a>' if example else ''

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
