const DAYS = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

function getFullname(key, names) {
    const tmp = key.split('-');
    const symbol = tmp[tmp.length - 1];
    const exchange = tmp[1];
    const secType = tmp[0];

    if (symbol === '[*]') {
        return FULLNAME_MAP[key] || secType;
    }

    const name = (names && names[symbol]) || symbol;
    const assetClass = secType.replace('Cfd', 'CFD').replace('IndexOption', 'US Index Option');
    const label = {
        'cfd': 'contract in the ' + assetClass.toUpperCase(),
        'forex': 'pair in the ' + assetClass,
        'index': '',
        'indexoption': 'Option contracts'
    }[secType.toLowerCase()];

    if (label !== undefined) {
        return (name + ' ' + label).trim();
    }
    return name + ' contract in the ' + exchange.toUpperCase() + ' ' + assetClass;
}

const FULLNAME_MAP = {
    'Cfd-interactivebrokers-[*]': 'CFD',
    'Cfd-oanda-[*]': 'CFD',
    'Equity-usa-[*]': 'US Equity',
    'Equity-india-[*]': 'India Equity',
    'Option-usa-[*]': 'Equity Option',
    'Index-usa-[*]': 'US Indices',
    'Index-eurex-[*]': 'EUREX Indices',
    'Index-hkfe-[*]': 'HKFE Indices',
    'Index-ose-[*]': 'Japan Indices',
    'IndexOption-usa-[*]': 'US Index Option'
};

function fixEnd(t) {
    return t.replace('1.00:00:00', '24:00:00').replace('1:00:00:00', '24:00:00');
}

function hoursTable(category, fullname, timezone, entry) {
    let lines = '';
    for (const day of DAYS) {
        const segments = entry[day];
        if (!segments || segments.length === 0) continue;
        const times = segments.map(s => fixEnd(s.start) + ' to ' + fixEnd(s.end)).join(', ');
        lines += '<tr><td>' + day.charAt(0).toUpperCase() + day.slice(1) + '</td><td>' + times + '</td></tr>\n';
    }
    if (!lines) return null;

    return '<p>The following table shows the ' + category + ' hours for the ' + fullname + ' market:</p>\n' +
        '<table class="table qc-table table-reflow">\n' +
        '<thead>\n<tr><th style="width: 20%;">Weekday</th><th style="width: 80%;">Time (' + timezone + ')</th></tr>\n</thead>\n' +
        '<tbody>\n' + lines + '</tbody>\n</table>';
}

function extractHours(entry, state) {
    const result = {};
    for (const day of DAYS) {
        const segments = (entry[day] || []).filter(s => s.state === state);
        if (segments.length > 0) result[day] = segments;
    }
    return Object.keys(result).length > 0 ? result : null;
}

function renderHoursSection(divId, category, fullname, timezone, entry, state) {
    const el = document.getElementById(divId);
    if (!el) return;
    const data = extractHours(entry, state);
    if (!data) {
        const msgs = {
            'premarket': '<p>Pre-market trading is not available.</p>',
            'market': '',
            'postmarket': '<p>Post-market trading is not available.</p>'
        };
        el.innerHTML = msgs[state] || '';
        return;
    }
    el.innerHTML = hoursTable(category, fullname, timezone, data) || '';
}

function parseDate(str) {
    const parts = str.split('/');
    return new Date(parseInt(parts[2]), parseInt(parts[0]) - 1, parseInt(parts[1]));
}

function formatDate(d) {
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return y + '-' + m + '-' + day;
}

function renderHolidays(divId, fullname, holidays, isUsEquity) {
    const el = document.getElementById(divId);
    if (!el) return;
    if (!holidays || holidays.length === 0) {
        el.innerHTML = '<p>There are no holidays for this market.</p>';
        return;
    }
    const cutoff = new Date();
    cutoff.setFullYear(cutoff.getFullYear() + 1);
    const dates = holidays
        .map(parseDate)
        .filter(d => d < cutoff)
        .sort((a, b) => a - b);

    if (dates.length === 0) {
        el.innerHTML = '<p>There are no holidays for this market.</p>';
        return;
    }

    let nyse = '';
    if (isUsEquity) {
        nyse = "<p>LEAN uses the <a target='_blank' rel='nofollow' href='https://www.nyse.com/markets/hours-calendars'>trading holidays</a> from the NYSE website.</p>\n";
    }

    let rows = '';
    for (let i = 0; i < dates.length; i += 5) {
        rows += '<tr>' + dates.slice(i, i + 5).map(d => '<td>' + formatDate(d) + '</td>').join('') + '</tr>\n';
    }

    el.innerHTML = nyse +
        '<p>The following table shows the dates of holidays for the ' + fullname + ' market:</p>\n' +
        '<table class="table qc-table table-reflow">\n' +
        '<thead><tr><th colspan="5">Date (<i>yyyy-mm-dd</i>)</th></tr></thead>\n' +
        '<tbody>\n' + rows + '</tbody>\n</table>';
}

function renderEarlyCloses(divId, fullname, timezone, earlyCloses) {
    const el = document.getElementById(divId);
    if (!el) return;
    if (!earlyCloses || Object.keys(earlyCloses).length === 0) {
        el.innerHTML = '<p>There are no days with early closes.</p>';
        return;
    }
    const cutoff = new Date();
    cutoff.setFullYear(cutoff.getFullYear() + 1);
    const entries = Object.entries(earlyCloses)
        .map(([date, time]) => [parseDate(date), time])
        .filter(([d]) => d < cutoff)
        .sort((a, b) => a[0] - b[0]);

    if (entries.length === 0) {
        el.innerHTML = '<p>There are no days with early closes.</p>';
        return;
    }

    let rows = '';
    for (const [date, time] of entries) {
        rows += '<tr><td>' + formatDate(date) + '</td><td>' + time + '</td></tr>\n';
    }

    el.innerHTML = '<p>The following table shows the early closes for the ' + fullname + ' market:</p>\n' +
        '<table class="table qc-table table-reflow">\n' +
        '<thead>\n<tr><th style="width: 50%;">Date (<i>yyyy-mm-dd</i>)</th><th style="width: 50%;">Time Of Market Close (' + timezone + ')</th></tr>\n</thead>\n' +
        '<tbody>\n' + rows + '</tbody>\n</table>';
}

function renderLateOpens(divId, fullname, timezone, lateOpens) {
    const el = document.getElementById(divId);
    if (!el) return;
    if (!lateOpens || Object.keys(lateOpens).length === 0) {
        el.innerHTML = '<p>There are no days with late opens.</p>';
        return;
    }
    const cutoff = new Date();
    cutoff.setFullYear(cutoff.getFullYear() + 1);
    const entries = Object.entries(lateOpens)
        .map(([date, time]) => [parseDate(date), time])
        .filter(([d]) => d < cutoff)
        .sort((a, b) => a[0] - b[0]);

    if (entries.length === 0) {
        el.innerHTML = '<p>There are no days with late opens.</p>';
        return;
    }

    let rows = '';
    for (const [date, time] of entries) {
        rows += '<tr><td>' + formatDate(date) + '</td><td>' + time + '</td></tr>\n';
    }

    el.innerHTML = '<p>The following table shows the late opens for the ' + fullname + ' market:</p>\n' +
        '<table class="table qc-table table-reflow">\n' +
        '<thead>\n<tr><th style="width: 50%;">Date (<i>yyyy-mm-dd</i>)</th><th style="width: 50%;">Time Of Market Open (' + timezone + ')</th></tr>\n</thead>\n' +
        '<tbody>\n' + rows + '</tbody>\n</table>';
}

function renderTimeZone(divId, fullname, timezone) {
    const el = document.getElementById(divId);
    if (!el) return;
    el.innerHTML = '<p>The ' + fullname + ' market trades in the <code>' + timezone + '</code> time zone.</p>';
}

function render(key, prefix, names, isUsEquity) {
    const entry = DATA[key];
    if (!entry) return;
    const tz = entry.exchangeTimeZone.replace(/_/g, ' ');
    const fullname = getFullname(key, names);

    const descEl = document.getElementById(prefix + '-description');
    if (descEl) {
        let intro = '<p>This page shows the trading hours, holidays, and time zone of the ' + fullname + ' market.</p>';
        const tmp = key.split('-');
        if (tmp[0] === 'Future' && ['ice', 'india', 'sgx', 'nyseliffe'].indexOf(tmp[1]) >= 0) {
            intro += "\n<p>Historical data for backtesting is unavailable for " + tmp[1].toUpperCase() +
                ". In live trading, LEAN sources this data from your <a href='/docs/v2/cloud-platform/live-trading/brokerages'>brokerage</a>" +
                " or a <a href='/docs/v2/writing-algorithms/live-trading/data-providers'>third-party data provider</a>.</p>";
        }
        descEl.innerHTML = intro;
    }

    renderHoursSection(prefix + '-pre-market', 'pre-market', fullname, tz, entry, 'premarket');
    renderHoursSection(prefix + '-regular', 'regular trading', fullname, tz, entry, 'market');
    renderHoursSection(prefix + '-post-market', 'post-market', fullname, tz, entry, 'postmarket');
    renderHolidays(prefix + '-holidays', fullname, entry.holidays, isUsEquity);
    renderEarlyCloses(prefix + '-early-closes', fullname, tz, entry.earlyCloses);
    renderLateOpens(prefix + '-late-opens', fullname, tz, entry.lateOpens);
    renderTimeZone(prefix + '-time-zone', fullname, tz);
}

function buildSymbolIndex(prefix, names) {
    const symbolMap = {};
    for (const key of Object.keys(DATA)) {
        const tmp = key.split('-');
        const symbol = tmp[tmp.length - 1];
        if (symbol === '[*]') continue;
        symbolMap[symbol] = key;
    }

    const datalist = document.getElementById(prefix + '-symbols');
    if (datalist) {
        const symbols = Object.keys(symbolMap).sort();
        for (const symbol of symbols) {
            const key = symbolMap[symbol];
            const tmp = key.split('-');
            const exchange = tmp[1].toUpperCase();
            const name = (names && names[symbol]) || symbol;
            const option = document.createElement('option');
            option.value = symbol;
            option.textContent = symbol + ' \u2014 ' + name + ' (' + exchange + ')';
            datalist.appendChild(option);
        }
    }

    return symbolMap;
}

function renderDefault() {
    const hasSymbols = Object.keys(DATA).length > 1;
    const names = (typeof NAMES !== 'undefined') ? NAMES : {};
    const isUsEquity = DEFAULT_KEY.startsWith('Equity-usa');

    render(DEFAULT_KEY, ID_PREFIX, names, isUsEquity);

    if (hasSymbols) {
        const symbolMap = buildSymbolIndex(ID_PREFIX, names);
        const input = document.getElementById(ID_PREFIX + '-symbol-search');
        if (input) {
            input.addEventListener('input', function () {
                const val = this.value.trim().toUpperCase();
                if (symbolMap[val]) {
                    render(symbolMap[val], ID_PREFIX, names, false);
                    if (window.history && window.history.replaceState) {
                        window.history.replaceState(null, '', '#' + val);
                    }
                } else if (val === '' || val === '[*]') {
                    render(DEFAULT_KEY, ID_PREFIX, names, isUsEquity);
                    if (window.history && window.history.replaceState) {
                        window.history.replaceState(null, '', window.location.pathname);
                    }
                }
            });

            // Check URL hash on load
            const hash = window.location.hash.replace('#', '').trim().toUpperCase();
            if (hash && symbolMap[hash]) {
                input.value = hash;
                render(symbolMap[hash], ID_PREFIX, names, false);
            }
        }
    }
}
