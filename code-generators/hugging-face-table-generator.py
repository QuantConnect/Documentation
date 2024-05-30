LIST_DIR = ['models--ProsusAI--finbert',
 'models--StephanAkkerman--FinTwitBERT-sentiment',
 'models--ahmedrachid--FinancialBERT-Sentiment-Analysis',
 'models--bardsai--finance-sentiment-fr-base',
 'models--cardiffnlp--twitter-roberta-base-sentiment-latest',
 'models--distilbert--distilbert-base-uncased',
 'models--google-bert--bert-base-uncased',
 'models--mrm8488--distilroberta-finetuned-financial-news-sentiment-analysis',
 'models--nickmuchi--deberta-v3-base-finetuned-finance-text-classification',
 'models--nickmuchi--distilroberta-finetuned-financial-text-classification',
 'models--nickmuchi--sec-bert-finetuned-finance-classification',
 'models--openai-community--gpt2',
 'models--yiyanghkust--finbert-tone',
 'models--FacebookAI--roberta-base',
 'models--microsoft--deberta-base',
 'version.txt',
 'models--google--timesfm-1.0-200m',
 'models--google--gemma-7b']

def __to_row(path):
    url = '/'.join(path.split('--')[1:])
    return f'<tr><td>{url}</td><td class="centered"><a rel="nofollow" target="_blank" href="https://huggingface.co/{url}"><i class="fa fa-external-link"></i></a></td></tr>'

if __name__ == '__main__':
    with open("Resources/machine-learning/hugging-face-table.html", mode='w') as f:
        rows = '\n        '.join(sorted([__to_row(x) for x in LIST_DIR if '.' not in x]))
        f.write(f'''<table class="qc-table table">
    <thead><tr><th>Name</th><th>Documentation</th></tr></thead>
    <tbody>
        {rows}
    </tbody>
</table>       
''')