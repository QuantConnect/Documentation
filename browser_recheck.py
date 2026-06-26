# QUANTCONNECT.COM - Democratizing Finance, Empowering Individuals.
# Lean Algorithmic Trading Engine v2.0. Copyright 2014 QuantConnect Corporation.
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.

# Shared browser-impersonating re-check, used by both url_check.py (this repo's
# own pages) and external_url_check.py (the Lean.Brokerages.*/Lean.DataSource.*
# repos). Many sites serve 401/403 to plain HTTP clients based on TLS/header
# fingerprinting even though the link is valid; curl_cffi mimics a real browser
# (Chrome TLS fingerprint) and gets through, so a 401/403 can be re-checked before
# it's reported.

import asyncio

from curl_cffi.requests import AsyncSession


async def browser_recheck(url: str) -> str:
    """Re-check a 401/403 with a browser-impersonating client (Chrome TLS fingerprint).

    Returns:
      - "ok"      : a real 2xx (or 429 - server is live, just rate-limiting)
      - "broken"  : a definitive 404/410 the block was hiding (a genuinely dead link)
      - "blocked" : still can't verify (e.g. a hard WAF) - report but don't fail
    """
    for attempt in range(3):
        try:
            async with AsyncSession() as s:
                r = await s.get(url, impersonate="chrome", timeout=30, allow_redirects=True)
            code = r.status_code
            if 200 <= code < 300:
                return "broken" if str(r.url).rstrip("/").endswith("/404") else "ok"
            if code in (404, 410):
                return "broken"        # the block was hiding a genuinely dead link
            if code == 429:
                return "ok"            # rate-limited => server is live, link exists
            # 401/403/5xx: transient block or rate-limit - back off and retry
        except Exception:
            pass
        await asyncio.sleep(2 * (attempt + 1))   # 2s, 4s
    return "blocked"
