<p>
  Hundreds of thousands of Hugging Face models are publicly available and ready to use. 
  To view all of them, see the <a href='https://huggingface.co/models' rel='nofollow' target='_blank'>Model Hub</a> on the Hugging Face website. 
  The <span class='tab-name'>Model card</span> tab of each model repository explains an overview of how the model works, its requirements, and a quick start guide.
</p>
<p>
  QuantConnect Cloud caches some of the most popular models to speed up your development workflow. 
  The following table shows the cached models:
</p>

<? include(DOCS_RESOURCES."/machine-learning/hugging-face-table.html"); ?>

<p>To see the commit hash of the cached models, run the following algorithm in QC Cloud and then <a href='/docs/v2/writing-algorithms/logging#07-Get-Logs'>view the logs</a>:</p>

<div class="section-example-container">
<pre class="python"># Scan and log all cached Hugging Face model revisions from the cache directory.
from huggingface_hub import scan_cache_dir

class HuggingFaceModelHashAlgorithm(QCAlgorithm):

    def initialize(self):
        cache_info = scan_cache_dir()
        cached_models_log = []
        for entry in cache_info.repos:
            revisions = [revision.commit_hash for revision in entry.revisions]
            cached_models_log.append(f'Repo: {entry.repo_id}. Revisions {str(revisions)}')
        self.quit('\n'.join(sorted(cached_models_log)))</pre> 
</div>


