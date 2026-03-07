function getLibrariesData() {
    if (typeof defined_python_data !== 'undefined' && typeof defined_csharp_data !== 'undefined') {
        return Promise.resolve([defined_python_data, defined_csharp_data]);
    }
    const cdnUrl = 'https://s3.amazonaws.com/cdn.quantconnect.com/web/docs/environment-packages-';
    return Promise.all([
        fetch(cdnUrl + 'python.json').then(r => r.json()),
        fetch(cdnUrl + 'csharp.json').then(r => r.json())
    ]);
}

function packagesToCodeBlock(packages) {
    let maxLen = 0;
    for (const p of packages) {
        if (p.name.length > maxLen) maxLen = p.name.length;
    }
    const sorted = [...packages].sort((a, b) => a.name.toLowerCase().localeCompare(b.name.toLowerCase()));
    let text = '';
    for (const p of sorted) {
        text += p.name + ' '.repeat(maxLen - p.name.length) + ' ' + p.version + '\n';
    }
    return text;
}

function titleCase(key) {
    return key.replace(/[-_]/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
}

function renderLibrariesWithButtons(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    container.innerHTML = '<p>Loading libraries...</p>';

    getLibrariesData()
        .then(([pythonData, csharpData]) => {
            const envKeys = Object.keys(pythonData);
            const csharpBlock = '<pre class="csharp">\n' + packagesToCodeBlock(csharpData['default']) + '</pre>';

            // Build buttons
            let contentHtml = '<div class="env-button-group python">';
            for (const key of envKeys) {
                const label = titleCase(key);
                const activeClass = key === 'default' ? ' active' : '';
                contentHtml += `<button class="env-button${activeClass}" data-env="${key}">${label}</button>`;
            }
            contentHtml += '</div>';

            // Build content for each environment
            for (const key of envKeys) {
                const display = key === 'default' ? 'block' : 'none';
                contentHtml += `<div class="env-content" id="env-${key}" style="display:${display}">`;
                contentHtml += '<div class="section-example-container">';
                contentHtml += '<pre class="python">\n' + packagesToCodeBlock(pythonData[key]) + '</pre>';
                contentHtml += csharpBlock;
                contentHtml += '</div></div>';
            }

            container.innerHTML = contentHtml;

            // Button click handlers
            const buttons = container.querySelectorAll('.env-button');
            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    buttons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    container.querySelectorAll('.env-content').forEach(el => el.style.display = 'none');
                    container.querySelector('#env-' + btn.dataset.env).style.display = 'block';
                });
            });
        });
}
