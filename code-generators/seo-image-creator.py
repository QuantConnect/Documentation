import os
import re
from datetime import datetime as dt
from pathlib import Path
from PIL import Image, ImageDraw, ImageFont
from _code_generation_helpers import get_lean_cli_commands

import numpy as np
import matplotlib
matplotlib.use('Agg')
import matplotlib.pyplot as plt
from matplotlib.ticker import FuncFormatter
from html.parser import HTMLParser
from io import BytesIO

PRODUCTS = [
    '01 Cloud Platform',
    '02 Local Platform',
    '03 Writing Algorithms',
    '04 Research Environment',
    '05 Lean CLI',
    '06 LEAN Engine'
]

LEAN_CLI_COMMAND_GROUPS = {
    'Authentication': [
        'lean login', 'lean init', 'lean whoami', 'lean logout'
    ],
    'Configuration': [
        'lean config list', 'lean config get',
        'lean config set', 'lean config unset'
    ],
    'Projects': [
        'lean create-project', 'lean delete-project',
        'lean cloud pull', 'lean cloud push',
        'lean library add', 'lean library remove'
    ],
    'Data': [
        'lean data download', 'lean data generate'
    ],
    'Research': [
        'lean research'
    ],
    'Backtesting': [
        'lean backtest', 'lean cloud backtest',
        'lean optimize', 'lean cloud optimize'
    ],
    'Live Trading': [
        'lean live deploy', 'lean live stop',
        'lean live liquidate',
        'lean cloud live deploy', 'lean cloud live stop',
        'lean cloud live liquidate', 'lean cloud status'
    ],
    'Results': [
        'lean logs', 'lean report'
    ],
}

class ImageGenerator:
    def __init__(self, fonts: list, template: str):
        self.canvas = Image.open(template)
        self.fonts = fonts
        self.x = 63
        self.y = 256
        self.max_text_width = self.canvas.size[0]-self.x*2

    def AddTextToImage(self, lines, outputfile):
        font = self.fonts[max(0, len(lines) - 4)]
        dx, dy = .8 * font.size, 1.2 * font.size

        image = self.canvas.copy()
        I1 = ImageDraw.Draw(image)
        for i, line in enumerate(lines):
            xy = (self.x + i * dx, self.y + i * dy)
            I1.text(xy, line, fill='#000', font=font)
        image.save(f'{outputfile}.png')
        image.close()

def _generate_lean_cli_cheat_sheet(location):
    """Generate a 1200x630 LEAN CLI API cheat sheet image."""
    width, height = 1200, 630
    bg_color = (255, 255, 255, 255)
    title_color = (30, 30, 30, 255)
    heading_color = (0, 120, 200, 255)
    cmd_color = (30, 30, 30, 255)
    desc_color = (100, 100, 100, 255)
    accent_color = (0, 120, 200, 255)

    font_path = f'{location}Inter-Bold.ttf'
    title_font = ImageFont.FreeTypeFont(font_path, 26)
    heading_font = ImageFont.FreeTypeFont(font_path, 12)
    cmd_font = ImageFont.FreeTypeFont(font_path, 10)
    desc_font = ImageFont.truetype(font_path, 9)

    # Fetch descriptions from LEAN CLI README
    all_commands = get_lean_cli_commands()
    for group, cmds in LEAN_CLI_COMMAND_GROUPS.items():
        for cmd in cmds:
            if cmd not in all_commands:
                print(f'Warning: "{cmd}" in group "{group}" not found in LEAN CLI README')

    image = Image.new('RGBA', (width, height), bg_color)
    draw = ImageDraw.Draw(image)

    # Title
    draw.text((40, 20), 'LEAN CLI API Cheat Sheet', fill=title_color, font=title_font)
    draw.rectangle([(40, 52), (300, 54)], fill=accent_color)

    # Layout: 3 columns
    col_x = [40, 420, 800]
    groups = list(LEAN_CLI_COMMAND_GROUPS.items())

    # Distribute groups across 3 columns
    # Column 0: Authentication, Configuration, Projects, Data
    # Column 1: Research, Backtesting, Live Trading
    # Column 2: Results (remaining groups can be added here)
    columns = [
        groups[0:4],
        groups[4:7],
        groups[7:],
    ]

    line_height = 14

    for col_idx, col_groups in enumerate(columns):
        x = col_x[col_idx]
        y = 70

        for group_name, commands in col_groups:
            # Group heading
            draw.text((x, y), group_name.upper(), fill=heading_color, font=heading_font)
            y += 18

            for cmd in commands:
                # Command name (strip "lean " prefix for brevity)
                short_cmd = cmd[5:]  # remove "lean "
                desc = all_commands.get(cmd, '')
                draw.text((x + 8, y), short_cmd, fill=cmd_color, font=cmd_font)
                # Description after a dash
                if desc:
                    cmd_width = draw.textlength(short_cmd, font=cmd_font)
                    draw.text((x + 8 + cmd_width + 6, y + 1), f'— {desc}', fill=desc_color, font=desc_font)
                y += line_height

            y += 10

    # Footer
    draw.text((40, height - 25), 'quantconnect.com/docs/v2/lean-cli', fill=(150, 150, 150, 255), font=desc_font)

    output_path = f'{location}lean-cli.png'
    image.save(output_path)
    image.close()
    print(f'LEAN CLI cheat sheet saved to {output_path}')

# ---------------------------------------------------------------------------
# Option Strategy Payoff Chart Generator
# ---------------------------------------------------------------------------

OPTION_STRATEGIES_DIR = '03 Writing Algorithms/22 Trading and Orders/08 Option Strategies'


class PayoffTableParser(HTMLParser):
    """Parse the <table id='payoff-table'> from 04 Example.html files."""

    def __init__(self):
        super().__init__()
        self.in_table = False
        self.in_thead = False
        self.in_tbody = False
        self.in_td = False
        self.in_th = False
        self.current_row = []
        self.headers = []
        self.rows = []
        self._buf = ''

    def handle_starttag(self, tag, attrs):
        attrs_dict = dict(attrs)
        if tag == 'table' and attrs_dict.get('id') == 'payoff-table':
            self.in_table = True
        if not self.in_table:
            return
        if tag == 'thead':
            self.in_thead = True
        elif tag == 'tbody':
            self.in_tbody = True
        elif tag == 'th':
            self.in_th = True
            self._buf = ''
        elif tag == 'td':
            self.in_td = True
            self._buf = ''

    def handle_endtag(self, tag):
        if not self.in_table:
            return
        if tag == 'th' and self.in_th:
            self.headers.append(self._buf.strip())
            self.in_th = False
        elif tag == 'td' and self.in_td:
            self.current_row.append(self._buf.strip())
            self.in_td = False
        elif tag == 'tr':
            if self.in_tbody and self.current_row:
                self.rows.append(list(self.current_row))
            self.current_row = []
        elif tag == 'thead':
            self.in_thead = False
        elif tag == 'tbody':
            self.in_tbody = False
        elif tag == 'table':
            self.in_table = False

    def handle_data(self, data):
        if self.in_th or self.in_td:
            self._buf += data

    def handle_entityref(self, name):
        if self.in_th or self.in_td:
            self._buf += f'&{name};'

    def handle_charref(self, name):
        if self.in_th or self.in_td:
            self._buf += f'&#{name};'


def _parse_example_table(html_path):
    """Return a list of dicts from the payoff table."""
    with open(html_path, encoding='utf-8') as f:
        html = f.read()

    parser = PayoffTableParser()
    parser.feed(html)

    def _to_float(s):
        s = s.strip().replace(',', '').replace('$', '')
        if s == '-' or s == '':
            return None
        return float(s)

    num_cols = len(parser.headers)
    results = []

    if num_cols == 4:
        for row in parser.rows:
            if len(row) < 4:
                continue
            results.append({
                'asset': row[0].strip(),
                'price_open': _to_float(row[1]),
                'price_expiry': _to_float(row[2]),
                'strike': _to_float(row[3]),
            })
    else:
        for row in parser.rows:
            if len(row) < 3:
                continue
            results.append({
                'asset': row[0].strip(),
                'price': _to_float(row[1]),
                'strike': _to_float(row[2]),
            })

    return results


def _classify_strategy(dir_name):
    """Return a strategy key from the numbered directory name."""
    name = ' '.join(dir_name.split(' ')[1:])
    return name.lower().replace(' ', '-')


def _get_slug(dir_name):
    """Return the URL slug used for the CDN path and file name."""
    return _classify_strategy(dir_name)


def _find_row(data, *keywords):
    """Find first row whose 'asset' field contains ALL keywords (case-insensitive)."""
    for r in data:
        asset_lower = r['asset'].lower()
        if all(kw.lower() in asset_lower for kw in keywords):
            return r
    return None


def _get_price(row):
    """Get the option price from a row (handles both 3-col and 4-col)."""
    if 'price' in row:
        return row['price']
    if 'price_open' in row:
        return row['price_open']
    return None


def _get_underlying_price(data):
    """Get the underlying price at trade open (S0)."""
    for r in data:
        a = r['asset'].lower()
        if 'underlying' in a and ('start' in a or 'opens' in a or 'open' in a):
            return _get_price(r)
    for r in data:
        a = r['asset'].lower()
        if 'underlying' in a and 'expir' in a:
            return _get_price(r)
    for r in data:
        a = r['asset'].lower()
        if 'underlying' in a:
            p = _get_price(r)
            if p is not None:
                return p
            if 'price_expiry' in r and r['price_expiry'] is not None:
                return r['price_expiry']
    return None


def _get_expiration_price(data):
    """Get the underlying price at expiration (S_T)."""
    for r in data:
        a = r['asset'].lower()
        if 'underlying' in a and 'expir' in a:
            return _get_price(r)
    for r in data:
        a = r['asset'].lower()
        if 'underlying' in a:
            if 'price_expiry' in r and r['price_expiry'] is not None:
                return r['price_expiry']
            return _get_price(r)
    return None


def _compute_payoff(strategy_key, data, S):
    """Compute per-share payoff for the given strategy.

    Returns (payoff_array, strikes_dict, premiums_dict, breakevens_list, S0).
    """
    strikes = {}
    premiums = {}
    S0 = _get_underlying_price(data)

    def call_payoff(K):
        return np.maximum(S - K, 0)

    def put_payoff(K):
        return np.maximum(K - S, 0)

    # GROUP 1: Vertical Spreads
    if strategy_key == 'bear-call-spread':
        otm = _find_row(data, 'OTM', 'call')
        itm = _find_row(data, 'ITM', 'call')
        K_otm, C_otm = otm['strike'], otm['price']
        K_itm, C_itm = itm['strike'], itm['price']
        payoff = (call_payoff(K_otm) - call_payoff(K_itm) + C_itm - C_otm)
        strikes = {'ITM Call': K_itm, 'OTM Call': K_otm}
        premiums = {'ITM Call premium': C_itm, 'OTM Call premium': C_otm}
        net_credit = C_itm - C_otm
        be = K_itm + net_credit
        breakevens = [be]

    elif strategy_key == 'bear-put-spread':
        otm = _find_row(data, 'OTM', 'put')
        itm = _find_row(data, 'ITM', 'put')
        K_otm, P_otm = otm['strike'], otm['price']
        K_itm, P_itm = itm['strike'], itm['price']
        payoff = (put_payoff(K_itm) - put_payoff(K_otm) - P_itm + P_otm)
        strikes = {'ITM Put': K_itm, 'OTM Put': K_otm}
        premiums = {'ITM Put premium': P_itm, 'OTM Put premium': P_otm}
        net_debit = P_itm - P_otm
        be = K_itm - net_debit
        breakevens = [be]

    elif strategy_key == 'bull-call-spread':
        otm = _find_row(data, 'OTM', 'call')
        itm = _find_row(data, 'ITM', 'call')
        K_otm, C_otm = otm['strike'], otm['price']
        K_itm, C_itm = itm['strike'], itm['price']
        payoff = (call_payoff(K_itm) - call_payoff(K_otm) - C_itm + C_otm)
        strikes = {'ITM Call': K_itm, 'OTM Call': K_otm}
        premiums = {'ITM Call premium': C_itm, 'OTM Call premium': C_otm}
        net_debit = C_itm - C_otm
        be = K_itm + net_debit
        breakevens = [be]

    elif strategy_key == 'bull-put-spread':
        otm = _find_row(data, 'OTM', 'put')
        itm = _find_row(data, 'ITM', 'put')
        K_otm, P_otm = otm['strike'], otm['price']
        K_itm, P_itm = itm['strike'], itm['price']
        payoff = (put_payoff(K_otm) - put_payoff(K_itm) + P_itm - P_otm)
        strikes = {'ITM Put': K_itm, 'OTM Put': K_otm}
        premiums = {'ITM Put premium': P_itm, 'OTM Put premium': P_otm}
        net_credit = P_itm - P_otm
        be = K_itm - net_credit
        breakevens = [be]

    # GROUP 2: Butterflies
    elif strategy_key == 'long-call-butterfly':
        otm = _find_row(data, 'OTM', 'call')
        atm = _find_row(data, 'ATM', 'call')
        itm = _find_row(data, 'ITM', 'call')
        K_otm, C_otm = otm['strike'], otm['price']
        K_atm, C_atm = atm['strike'], atm['price']
        K_itm, C_itm = itm['strike'], itm['price']
        payoff = (call_payoff(K_otm) + call_payoff(K_itm) - 2 * call_payoff(K_atm)
                  + 2 * C_atm - C_itm - C_otm)
        strikes = {'OTM Call': K_otm, 'ATM Call': K_atm, 'ITM Call': K_itm}
        premiums = {'OTM': C_otm, 'ATM': C_atm, 'ITM': C_itm}
        net_debit = C_itm + C_otm - 2 * C_atm
        ks = sorted([K_otm, K_atm, K_itm])
        be_low = ks[0] + net_debit
        be_high = ks[2] - net_debit
        breakevens = [be_low, be_high]

    elif strategy_key == 'short-call-butterfly':
        otm = _find_row(data, 'OTM', 'call')
        atm = _find_row(data, 'ATM', 'call')
        itm = _find_row(data, 'ITM', 'call')
        K_otm, C_otm = otm['strike'], otm['price']
        K_atm, C_atm = atm['strike'], atm['price']
        K_itm, C_itm = itm['strike'], itm['price']
        payoff = (-call_payoff(K_otm) - call_payoff(K_itm) + 2 * call_payoff(K_atm)
                  - 2 * C_atm + C_itm + C_otm)
        strikes = {'OTM Call': K_otm, 'ATM Call': K_atm, 'ITM Call': K_itm}
        premiums = {'OTM': C_otm, 'ATM': C_atm, 'ITM': C_itm}
        net_credit = C_itm + C_otm - 2 * C_atm
        ks = sorted([K_otm, K_atm, K_itm])
        be_low = ks[0] + net_credit
        be_high = ks[2] - net_credit
        breakevens = [be_low, be_high]

    elif strategy_key == 'long-put-butterfly':
        otm = _find_row(data, 'OTM', 'put')
        atm = _find_row(data, 'ATM', 'put')
        itm = _find_row(data, 'ITM', 'put')
        K_otm, P_otm = otm['strike'], otm['price']
        K_atm, P_atm = atm['strike'], atm['price']
        K_itm, P_itm = itm['strike'], itm['price']
        payoff = (put_payoff(K_otm) + put_payoff(K_itm) - 2 * put_payoff(K_atm)
                  + 2 * P_atm - P_itm - P_otm)
        strikes = {'OTM Put': K_otm, 'ATM Put': K_atm, 'ITM Put': K_itm}
        premiums = {'OTM': P_otm, 'ATM': P_atm, 'ITM': P_itm}
        net_debit = P_itm + P_otm - 2 * P_atm
        ks = sorted([K_otm, K_atm, K_itm])
        be_low = ks[0] + net_debit
        be_high = ks[2] - net_debit
        breakevens = [be_low, be_high]

    elif strategy_key == 'short-put-butterfly':
        otm = _find_row(data, 'OTM', 'put')
        atm = _find_row(data, 'ATM', 'put')
        itm = _find_row(data, 'ITM', 'put')
        K_otm, P_otm = otm['strike'], otm['price']
        K_atm, P_atm = atm['strike'], atm['price']
        K_itm, P_itm = itm['strike'], itm['price']
        payoff = (-put_payoff(K_otm) - put_payoff(K_itm) + 2 * put_payoff(K_atm)
                  - 2 * P_atm + P_itm + P_otm)
        strikes = {'OTM Put': K_otm, 'ATM Put': K_atm, 'ITM Put': K_itm}
        premiums = {'OTM': P_otm, 'ATM': P_atm, 'ITM': P_itm}
        net_credit = P_itm + P_otm - 2 * P_atm
        ks = sorted([K_otm, K_atm, K_itm])
        be_low = ks[0] + net_credit
        be_high = ks[2] - net_credit
        breakevens = [be_low, be_high]

    # GROUP 3: Calendar Spreads
    elif strategy_key in ('long-call-calendar-spread', 'short-call-calendar-spread',
                          'long-put-calendar-spread', 'short-put-calendar-spread'):
        option_rows = [r for r in data if 'underlying' not in r['asset'].lower()]
        strikes_vals = set()
        for r in option_rows:
            k = r.get('strike')
            if k is not None:
                strikes_vals.add(k)

        K = list(strikes_vals)[0] if len(strikes_vals) == 1 else min(strikes_vals)

        is_long = 'long' in strategy_key

        if len(data[0]) > 3 or 'price_open' in data[0]:
            near_row = _find_row(data, 'near') or _find_row(data, 'shorter')
            far_row = _find_row(data, 'far') or _find_row(data, 'longer')
            if near_row and far_row:
                near_open = near_row.get('price_open', near_row.get('price', 0))
                far_open = far_row.get('price_open', far_row.get('price', 0))
                near_expiry = near_row.get('price_expiry', 0)
                far_expiry = far_row.get('price_expiry', 0)
            else:
                near_open = far_open = near_expiry = far_expiry = 0
        else:
            longer_start = _find_row(data, 'longer', 'start') or _find_row(data, 'longer')
            shorter_start = _find_row(data, 'shorter', 'start') or _find_row(data, 'shorter')
            longer_t = _find_row(data, 'longer', 'time')
            if longer_start and shorter_start:
                far_open = longer_start['price']
                near_open = shorter_start['price']
                far_expiry = longer_t['price'] if longer_t else far_open
                near_expiry = 0
            else:
                near_open = far_open = near_expiry = far_expiry = 0

        if is_long:
            net_cost = far_open - near_open
        else:
            net_cost = near_open - far_open

        wing_width = K * 0.10
        if is_long:
            max_loss = -abs(net_cost)
            max_profit_val = abs(far_expiry - far_open + near_open) - abs(net_cost)
            if max_profit_val <= 0:
                max_profit_val = abs(net_cost) * 0.5
            payoff = np.where(
                np.abs(S - K) <= wing_width,
                max_profit_val * (1 - (np.abs(S - K) / wing_width) ** 2),
                max_loss
            )
        else:
            max_gain = abs(net_cost)
            max_loss_val = -(abs(far_expiry - far_open + near_open) - abs(net_cost))
            if max_loss_val >= 0:
                max_loss_val = -abs(net_cost) * 0.5
            payoff = np.where(
                np.abs(S - K) <= wing_width,
                max_loss_val * (1 - (np.abs(S - K) / wing_width) ** 2),
                max_gain
            )

        strikes = {'Strike': K}
        premiums = {}
        breakevens = [K - wing_width * 0.7, K + wing_width * 0.7]

    # GROUP 4: Covered Call / Covered Put
    elif strategy_key == 'covered-call':
        call_row = _find_row(data, 'call')
        K, C0 = call_row['strike'], call_row['price']
        if S0 is None:
            S0 = _get_expiration_price(data)
        payoff = (S - S0) + C0 - call_payoff(K)
        strikes = {'Call Strike': K}
        premiums = {'Call premium': C0}
        be = S0 - C0
        breakevens = [be]

    elif strategy_key == 'covered-put':
        put_row = _find_row(data, 'put')
        K, P0 = put_row['strike'], put_row['price']
        if S0 is None:
            S0 = _get_expiration_price(data)
        payoff = (S0 - S) + P0 - put_payoff(K)
        strikes = {'Put Strike': K}
        premiums = {'Put premium': P0}
        be = S0 + P0
        breakevens = [be]

    # GROUP 5: Iron Butterflies
    elif strategy_key == 'long-iron-butterfly':
        otm_call = _find_row(data, 'OTM', 'call')
        otm_put = _find_row(data, 'OTM', 'put')
        atm_call = _find_row(data, 'ATM', 'call')
        atm_put = _find_row(data, 'ATM', 'put')
        K_oc, C_oc = otm_call['strike'], otm_call['price']
        K_op, P_op = otm_put['strike'], otm_put['price']
        K_ac, C_ac = atm_call['strike'], atm_call['price']
        K_ap, P_ap = atm_put['strike'], atm_put['price']
        payoff = (call_payoff(K_oc) + put_payoff(K_op)
                  - call_payoff(K_ac) - put_payoff(K_ap)
                  - C_oc - P_op + C_ac + P_ap)
        strikes = {'OTM Call': K_oc, 'ATM Call': K_ac, 'OTM Put': K_op, 'ATM Put': K_ap}
        premiums = {'OTM Call': C_oc, 'ATM Call': C_ac, 'OTM Put': P_op, 'ATM Put': P_ap}
        net_credit = C_ac + P_ap - C_oc - P_op
        be_low = K_ap - net_credit
        be_high = K_ac + net_credit
        breakevens = [be_low, be_high]

    elif strategy_key == 'short-iron-butterfly':
        otm_call = _find_row(data, 'OTM', 'call')
        otm_put = _find_row(data, 'OTM', 'put')
        atm_call = _find_row(data, 'ATM', 'call')
        atm_put = _find_row(data, 'ATM', 'put')
        K_oc, C_oc = otm_call['strike'], otm_call['price']
        K_op, P_op = otm_put['strike'], otm_put['price']
        K_ac, C_ac = atm_call['strike'], atm_call['price']
        K_ap, P_ap = atm_put['strike'], atm_put['price']
        payoff = (-call_payoff(K_oc) - put_payoff(K_op)
                  + call_payoff(K_ac) + put_payoff(K_ap)
                  + C_oc + P_op - C_ac - P_ap)
        strikes = {'OTM Call': K_oc, 'ATM Call': K_ac, 'OTM Put': K_op, 'ATM Put': K_ap}
        premiums = {'OTM Call': C_oc, 'ATM Call': C_ac, 'OTM Put': P_op, 'ATM Put': P_ap}
        net_debit = C_ac + P_ap - C_oc - P_op
        be_low = K_ap - net_debit
        be_high = K_ac + net_debit
        breakevens = [be_low, be_high]

    # GROUP 6: Iron Condors
    elif strategy_key == 'long-iron-condor':
        far_call = _find_row(data, 'far', 'call') or _find_row(data, 'Far-OTM', 'call')
        far_put = _find_row(data, 'far', 'put') or _find_row(data, 'Far-OTM', 'put')
        near_call = _find_row(data, 'near', 'call') or _find_row(data, 'Near-OTM', 'call')
        near_put = _find_row(data, 'near', 'put') or _find_row(data, 'Near-OTM', 'put')
        K_fc, C_fc = far_call['strike'], far_call['price']
        K_fp, P_fp = far_put['strike'], far_put['price']
        K_nc, C_nc = near_call['strike'], near_call['price']
        K_np, P_np = near_put['strike'], near_put['price']
        payoff = (call_payoff(K_fc) + put_payoff(K_fp)
                  - call_payoff(K_nc) - put_payoff(K_np)
                  - C_fc - P_fp + C_nc + P_np)
        strikes = {'Far Call': K_fc, 'Near Call': K_nc, 'Far Put': K_fp, 'Near Put': K_np}
        premiums = {'Far Call': C_fc, 'Near Call': C_nc, 'Far Put': P_fp, 'Near Put': P_np}
        net_credit = C_nc + P_np - C_fc - P_fp
        be_low = K_np - net_credit
        be_high = K_nc + net_credit
        breakevens = [be_low, be_high]

    elif strategy_key == 'short-iron-condor':
        far_call = _find_row(data, 'far', 'call') or _find_row(data, 'Far-OTM', 'call')
        far_put = _find_row(data, 'far', 'put') or _find_row(data, 'Far-OTM', 'put')
        near_call = _find_row(data, 'near', 'call') or _find_row(data, 'Near-OTM', 'call')
        near_put = _find_row(data, 'near', 'put') or _find_row(data, 'Near-OTM', 'put')
        K_fc, C_fc = far_call['strike'], far_call['price']
        K_fp, P_fp = far_put['strike'], far_put['price']
        K_nc, C_nc = near_call['strike'], near_call['price']
        K_np, P_np = near_put['strike'], near_put['price']
        payoff = (-call_payoff(K_fc) - put_payoff(K_fp)
                  + call_payoff(K_nc) + put_payoff(K_np)
                  + C_fc + P_fp - C_nc - P_np)
        strikes = {'Far Call': K_fc, 'Near Call': K_nc, 'Far Put': K_fp, 'Near Put': K_np}
        premiums = {'Far Call': C_fc, 'Near Call': C_nc, 'Far Put': P_fp, 'Near Put': P_np}
        net_debit = C_nc + P_np - C_fc - P_fp
        be_low = K_np - net_debit
        be_high = K_nc + net_debit
        breakevens = [be_low, be_high]

    # GROUP 7: Naked Options
    elif strategy_key == 'naked-call':
        call_row = _find_row(data, 'call')
        K, C0 = call_row['strike'], call_row['price']
        payoff = C0 - call_payoff(K)
        strikes = {'Call Strike': K}
        premiums = {'Call premium': C0}
        be = K + C0
        breakevens = [be]

    elif strategy_key == 'naked-put':
        put_row = _find_row(data, 'put')
        K, P0 = put_row['strike'], put_row['price']
        payoff = P0 - put_payoff(K)
        strikes = {'Put Strike': K}
        premiums = {'Put premium': P0}
        be = K - P0
        breakevens = [be]

    # GROUP 8: Protective Options
    elif strategy_key == 'protective-call':
        call_row = _find_row(data, 'call')
        K, C0 = call_row['strike'], call_row['price']
        if S0 is None:
            S0 = _get_expiration_price(data)
        payoff = (S0 - S) - C0 + call_payoff(K)
        strikes = {'Call Strike': K}
        premiums = {'Call premium': C0}
        be = S0 - C0
        breakevens = [be]

    elif strategy_key == 'protective-put':
        put_row = _find_row(data, 'put')
        K, P0 = put_row['strike'], put_row['price']
        if S0 is None:
            S0 = _get_expiration_price(data)
        payoff = (S - S0) - P0 + put_payoff(K)
        strikes = {'Put Strike': K}
        premiums = {'Put premium': P0}
        be = S0 + P0
        breakevens = [be]

    elif strategy_key == 'protective-collar':
        call_row = _find_row(data, 'call')
        put_row = _find_row(data, 'put')
        K_c, C0 = call_row['strike'], call_row['price']
        K_p, P0 = put_row['strike'], put_row['price']
        if S0 is None:
            S0 = _get_expiration_price(data)
        payoff = (S - S0) + put_payoff(K_p) - call_payoff(K_c) + C0 - P0
        strikes = {'Call Strike': K_c, 'Put Strike': K_p}
        premiums = {'Call premium': C0, 'Put premium': P0}
        be = S0 - C0 + P0
        breakevens = [be]

    # GROUP 9: Straddles
    elif strategy_key == 'long-straddle':
        call_row = _find_row(data, 'call')
        put_row = _find_row(data, 'put')
        K_c, C0 = call_row['strike'], call_row['price']
        K_p, P0 = put_row['strike'], put_row['price']
        K = K_c
        payoff = call_payoff(K) + put_payoff(K) - C0 - P0
        strikes = {'Strike': K}
        premiums = {'Call premium': C0, 'Put premium': P0}
        be_low = K - (C0 + P0)
        be_high = K + (C0 + P0)
        breakevens = [be_low, be_high]

    elif strategy_key == 'short-straddle':
        call_row = _find_row(data, 'call')
        put_row = _find_row(data, 'put')
        K_c, C0 = call_row['strike'], call_row['price']
        K_p, P0 = put_row['strike'], put_row['price']
        K = K_c
        payoff = -call_payoff(K) - put_payoff(K) + C0 + P0
        strikes = {'Strike': K}
        premiums = {'Call premium': C0, 'Put premium': P0}
        be_low = K - (C0 + P0)
        be_high = K + (C0 + P0)
        breakevens = [be_low, be_high]

    # GROUP 10: Strangles
    elif strategy_key == 'long-strangle':
        call_row = _find_row(data, 'call')
        put_row = _find_row(data, 'put')
        K_c, C0 = call_row['strike'], call_row['price']
        K_p, P0 = put_row['strike'], put_row['price']
        payoff = call_payoff(K_c) + put_payoff(K_p) - C0 - P0
        strikes = {'Call Strike': K_c, 'Put Strike': K_p}
        premiums = {'Call premium': C0, 'Put premium': P0}
        be_low = K_p - (C0 + P0)
        be_high = K_c + (C0 + P0)
        breakevens = [be_low, be_high]

    elif strategy_key == 'short-strangle':
        call_row = _find_row(data, 'call')
        put_row = _find_row(data, 'put')
        K_c, C0 = call_row['strike'], call_row['price']
        K_p, P0 = put_row['strike'], put_row['price']
        payoff = -call_payoff(K_c) - put_payoff(K_p) + C0 + P0
        strikes = {'Call Strike': K_c, 'Put Strike': K_p}
        premiums = {'Call premium': C0, 'Put premium': P0}
        be_low = K_p - (C0 + P0)
        be_high = K_c + (C0 + P0)
        breakevens = [be_low, be_high]

    # GROUP 11: Conversions / Reverse Conversions
    elif strategy_key == 'conversion':
        call_row = _find_row(data, 'call')
        put_row = _find_row(data, 'put')
        K = call_row['strike']
        C0 = call_row['price']
        P0 = put_row['price']
        if S0 is None:
            S0 = _get_expiration_price(data)
        payoff = (S - S0) + put_payoff(K) - call_payoff(K) + C0 - P0
        strikes = {'Strike': K}
        premiums = {'Call premium': C0, 'Put premium': P0}
        breakevens = []

    elif strategy_key == 'reverse-conversion':
        call_row = _find_row(data, 'call')
        put_row = _find_row(data, 'put')
        K = call_row['strike']
        C0 = call_row['price']
        P0 = put_row['price']
        if S0 is None:
            S0 = _get_expiration_price(data)
        payoff = (S0 - S) + call_payoff(K) - put_payoff(K) - C0 + P0
        strikes = {'Strike': K}
        premiums = {'Call premium': C0, 'Put premium': P0}
        breakevens = []

    # GROUP 12: Box Spreads
    elif strategy_key == 'long-box-spread':
        itm_call = _find_row(data, 'ITM', 'call')
        otm_call = _find_row(data, 'OTM', 'call')
        itm_put = _find_row(data, 'ITM', 'put')
        otm_put = _find_row(data, 'OTM', 'put')
        K_ic, C_ic = itm_call['strike'], itm_call['price']
        K_oc, C_oc = otm_call['strike'], otm_call['price']
        K_ip, P_ip = itm_put['strike'], itm_put['price']
        K_op, P_op = otm_put['strike'], otm_put['price']
        payoff = (call_payoff(K_ic) - call_payoff(K_oc)
                  + put_payoff(K_ip) - put_payoff(K_op)
                  - C_ic + C_oc - P_ip + P_op)
        strikes = {'Lower Strike': min(K_ic, K_oc, K_ip, K_op),
                   'Upper Strike': max(K_ic, K_oc, K_ip, K_op)}
        premiums = {'ITM Call': C_ic, 'OTM Call': C_oc, 'ITM Put': P_ip, 'OTM Put': P_op}
        breakevens = []

    elif strategy_key == 'short-box-spread':
        itm_call = _find_row(data, 'ITM', 'call')
        otm_call = _find_row(data, 'OTM', 'call')
        itm_put = _find_row(data, 'ITM', 'put')
        otm_put = _find_row(data, 'OTM', 'put')
        K_ic, C_ic = itm_call['strike'], itm_call['price']
        K_oc, C_oc = otm_call['strike'], otm_call['price']
        K_ip, P_ip = itm_put['strike'], itm_put['price']
        K_op, P_op = otm_put['strike'], otm_put['price']
        payoff = (-call_payoff(K_ic) + call_payoff(K_oc)
                  - put_payoff(K_ip) + put_payoff(K_op)
                  + C_ic - C_oc + P_ip - P_op)
        strikes = {'Lower Strike': min(K_ic, K_oc, K_ip, K_op),
                   'Upper Strike': max(K_ic, K_oc, K_ip, K_op)}
        premiums = {'ITM Call': C_ic, 'OTM Call': C_oc, 'ITM Put': P_ip, 'OTM Put': P_op}
        breakevens = []

    # GROUP 13: Jelly Rolls
    elif strategy_key in ('long-jelly-roll', 'short-jelly-roll'):
        option_rows = [r for r in data if 'underlying' not in r['asset'].lower()]
        K_vals = set()
        for r in option_rows:
            k = r.get('strike')
            if k is not None:
                K_vals.add(k)
        K = list(K_vals)[0] if K_vals else 0

        total_value = 0
        for r in option_rows:
            p_exp = r.get('price_expiry', 0)
            p_open = r.get('price_open', r.get('price', 0))
            if p_exp is None:
                p_exp = 0
            if p_open is None:
                p_open = 0
            a = r['asset'].lower()
            if strategy_key == 'long-jelly-roll':
                if ('near' in a and 'call' in a) or ('far' in a and 'put' in a):
                    total_value += (p_open - p_exp)
                else:
                    total_value += (p_exp - p_open)
            else:
                if ('near' in a and 'put' in a) or ('far' in a and 'call' in a):
                    total_value += (p_open - p_exp)
                else:
                    total_value += (p_exp - p_open)

        payoff = np.full_like(S, total_value)
        strikes = {'Strike': K}
        premiums = {}
        breakevens = []

    # GROUP 14: Ladders
    elif strategy_key == 'bear-call-ladder':
        low = _find_row(data, 'lower', 'call') or _find_row(data, 'low', 'call')
        mid = _find_row(data, 'middle', 'call') or _find_row(data, 'mid', 'call')
        high = _find_row(data, 'higher', 'call') or _find_row(data, 'high', 'call')
        K_l, C_l = low['strike'], low['price']
        K_m, C_m = mid['strike'], mid['price']
        K_h, C_h = high['strike'], high['price']
        payoff = (C_l - call_payoff(K_l) + call_payoff(K_m) - C_m + call_payoff(K_h) - C_h)
        strikes = {'Lower Call': K_l, 'Middle Call': K_m, 'Higher Call': K_h}
        premiums = {'Lower': C_l, 'Middle': C_m, 'Higher': C_h}
        net = C_l - C_m - C_h
        be1 = K_l + net
        be2 = K_m + K_h - K_l - net
        breakevens = [be1, be2]

    elif strategy_key == 'bear-put-ladder':
        low = _find_row(data, 'lower', 'put') or _find_row(data, 'low', 'put')
        mid = _find_row(data, 'middle', 'put') or _find_row(data, 'mid', 'put')
        high = _find_row(data, 'higher', 'put') or _find_row(data, 'high', 'put')
        K_l, P_l = low['strike'], low['price']
        K_m, P_m = mid['strike'], mid['price']
        K_h, P_h = high['strike'], high['price']
        payoff = (put_payoff(K_h) - P_h - put_payoff(K_m) + P_m - put_payoff(K_l) + P_l)
        strikes = {'Lower Put': K_l, 'Middle Put': K_m, 'Higher Put': K_h}
        premiums = {'Lower': P_l, 'Middle': P_m, 'Higher': P_h}
        net = P_m + P_l - P_h
        be1 = K_h - net if net < P_h else K_h + net - P_h - P_m - P_l
        be2 = K_l + K_m - K_h + net
        breakevens = [be2, be1]

    elif strategy_key == 'bull-call-ladder':
        low = _find_row(data, 'lower', 'call') or _find_row(data, 'low', 'call')
        mid = _find_row(data, 'middle', 'call') or _find_row(data, 'mid', 'call')
        high = _find_row(data, 'higher', 'call') or _find_row(data, 'high', 'call')
        K_l, C_l = low['strike'], low['price']
        K_m, C_m = mid['strike'], mid['price']
        K_h, C_h = high['strike'], high['price']
        payoff = (call_payoff(K_l) - C_l - call_payoff(K_m) + C_m - call_payoff(K_h) + C_h)
        strikes = {'Lower Call': K_l, 'Middle Call': K_m, 'Higher Call': K_h}
        premiums = {'Lower': C_l, 'Middle': C_m, 'Higher': C_h}
        net = C_m + C_h - C_l
        be1 = K_l + abs(net)
        be2 = K_m + K_h - K_l + net
        breakevens = [be1, be2]

    elif strategy_key == 'bull-put-ladder':
        low = _find_row(data, 'lower', 'put') or _find_row(data, 'low', 'put')
        mid = _find_row(data, 'middle', 'put') or _find_row(data, 'mid', 'put')
        high = _find_row(data, 'higher', 'put') or _find_row(data, 'high', 'put')
        K_l, P_l = low['strike'], low['price']
        K_m, P_m = mid['strike'], mid['price']
        K_h, P_h = high['strike'], high['price']
        payoff = (-put_payoff(K_h) + P_h + put_payoff(K_m) - P_m + put_payoff(K_l) - P_l)
        strikes = {'Lower Put': K_l, 'Middle Put': K_m, 'Higher Put': K_h}
        premiums = {'Lower': P_l, 'Middle': P_m, 'Higher': P_h}
        net = P_h - P_m - P_l
        be1 = K_h - net
        be2 = K_l + K_m - K_h + net
        breakevens = [be2, be1]

    # GROUP 15: Backspreads
    elif strategy_key == 'long-call-backspread':
        low = _find_row(data, 'lower', 'call') or _find_row(data, 'low', 'call')
        high = _find_row(data, 'higher', 'call') or _find_row(data, 'high', 'call')
        K_l, C_l = low['strike'], low['price']
        K_h, C_h = high['strike'], high['price']
        payoff = (C_l - call_payoff(K_l) + 2 * call_payoff(K_h) - 2 * C_h)
        strikes = {'Lower Call': K_l, 'Higher Call': K_h}
        premiums = {'Lower': C_l, 'Higher (x2)': C_h}
        net = C_l - 2 * C_h
        be1 = K_l + abs(net)
        be2 = 2 * K_h - K_l - net
        breakevens = [be1, be2]

    elif strategy_key == 'short-call-backspread':
        low = _find_row(data, 'lower', 'call') or _find_row(data, 'low', 'call')
        high = _find_row(data, 'higher', 'call') or _find_row(data, 'high', 'call')
        K_l, C_l = low['strike'], low['price']
        K_h, C_h = high['strike'], high['price']
        payoff = (-C_l + call_payoff(K_l) - 2 * call_payoff(K_h) + 2 * C_h)
        strikes = {'Lower Call': K_l, 'Higher Call': K_h}
        premiums = {'Lower': C_l, 'Higher (x2)': C_h}
        net = 2 * C_h - C_l
        be1 = K_l + abs(net)
        be2 = 2 * K_h - K_l - abs(net)
        breakevens = [be1, be2] if be1 != be2 else [be1]

    elif strategy_key == 'long-put-backspread':
        low = _find_row(data, 'lower', 'put') or _find_row(data, 'low', 'put')
        high = _find_row(data, 'higher', 'put') or _find_row(data, 'high', 'put')
        K_l, P_l = low['strike'], low['price']
        K_h, P_h = high['strike'], high['price']
        payoff = (2 * put_payoff(K_l) - 2 * P_l - put_payoff(K_h) + P_h)
        strikes = {'Lower Put': K_l, 'Higher Put': K_h}
        premiums = {'Lower (x2)': P_l, 'Higher': P_h}
        net = P_h - 2 * P_l
        be1 = 2 * K_l - K_h + abs(net)
        be2 = K_h - abs(net)
        breakevens = [be1, be2]

    elif strategy_key == 'short-put-backspread':
        low = _find_row(data, 'lower', 'put') or _find_row(data, 'low', 'put')
        high = _find_row(data, 'higher', 'put') or _find_row(data, 'high', 'put')
        K_l, P_l = low['strike'], low['price']
        K_h, P_h = high['strike'], high['price']
        payoff = (-2 * put_payoff(K_l) + 2 * P_l + put_payoff(K_h) - P_h)
        strikes = {'Lower Put': K_l, 'Higher Put': K_h}
        premiums = {'Lower (x2)': P_l, 'Higher': P_h}
        net = 2 * P_l - P_h
        be1 = 2 * K_l - K_h + abs(net)
        be2 = K_h - abs(net)
        breakevens = [be1, be2]

    else:
        raise ValueError(f'Unknown strategy: {strategy_key}')

    # Compute breakevens numerically: find where payoff crosses zero
    # This is more reliable than analytical formulas for complex strategies
    numerical_breakevens = []
    for i in range(len(S) - 1):
        if payoff[i] * payoff[i + 1] < 0:  # sign change
            # Linear interpolation to find exact crossing
            s_cross = S[i] - payoff[i] * (S[i + 1] - S[i]) / (payoff[i + 1] - payoff[i])
            numerical_breakevens.append(round(s_cross, 2))
    breakevens = numerical_breakevens

    return payoff, strikes, premiums, breakevens, S0


def _build_leg_lines(strategy_key, data, S):
    """Build individual leg payoff lines for decomposition display.

    Returns a list of dicts: [{'label': str, 'payoff': ndarray, 'color': str}, ...]
    """
    LEG_COLORS = ['#3498DB', '#E67E22', '#27AE60', '#E74C3C', '#9B59B6', '#1ABC9C']
    lines = []

    def call_intr(K):
        return np.maximum(S - K, 0)

    def put_intr(K):
        return np.maximum(K - S, 0)

    S0 = _get_underlying_price(data)

    # Vertical Spreads
    if strategy_key == 'bear-call-spread':
        itm = _find_row(data, 'ITM', 'call')
        otm = _find_row(data, 'OTM', 'call')
        lines.append({'label': f'Short ITM Call ({itm["price"]:,.2f})', 'payoff': itm['price'] - call_intr(itm['strike']), 'color': LEG_COLORS[0]})
        lines.append({'label': f'Long OTM Call ({otm["price"]:,.2f})', 'payoff': call_intr(otm['strike']) - otm['price'], 'color': LEG_COLORS[1]})

    elif strategy_key == 'bear-put-spread':
        itm = _find_row(data, 'ITM', 'put')
        otm = _find_row(data, 'OTM', 'put')
        lines.append({'label': f'Long ITM Put ({itm["price"]:,.2f})', 'payoff': put_intr(itm['strike']) - itm['price'], 'color': LEG_COLORS[0]})
        lines.append({'label': f'Short OTM Put ({otm["price"]:,.2f})', 'payoff': otm['price'] - put_intr(otm['strike']), 'color': LEG_COLORS[1]})

    elif strategy_key == 'bull-call-spread':
        itm = _find_row(data, 'ITM', 'call')
        otm = _find_row(data, 'OTM', 'call')
        lines.append({'label': f'Long ITM Call ({itm["price"]:,.2f})', 'payoff': call_intr(itm['strike']) - itm['price'], 'color': LEG_COLORS[0]})
        lines.append({'label': f'Short OTM Call ({otm["price"]:,.2f})', 'payoff': otm['price'] - call_intr(otm['strike']), 'color': LEG_COLORS[1]})

    elif strategy_key == 'bull-put-spread':
        itm = _find_row(data, 'ITM', 'put')
        otm = _find_row(data, 'OTM', 'put')
        lines.append({'label': f'Short ITM Put ({itm["price"]:,.2f})', 'payoff': itm['price'] - put_intr(itm['strike']), 'color': LEG_COLORS[0]})
        lines.append({'label': f'Long OTM Put ({otm["price"]:,.2f})', 'payoff': put_intr(otm['strike']) - otm['price'], 'color': LEG_COLORS[1]})

    # Butterflies
    elif strategy_key == 'long-call-butterfly':
        itm = _find_row(data, 'ITM', 'call')
        atm = _find_row(data, 'ATM', 'call')
        otm = _find_row(data, 'OTM', 'call')
        lines.append({'label': f'Long ITM Call ({itm["price"]:,.2f})', 'payoff': call_intr(itm['strike']) - itm['price'], 'color': LEG_COLORS[0]})
        lines.append({'label': f'Short 2x ATM Call ({atm["price"]:,.2f})', 'payoff': 2 * (atm['price'] - call_intr(atm['strike'])), 'color': LEG_COLORS[1]})
        lines.append({'label': f'Long OTM Call ({otm["price"]:,.2f})', 'payoff': call_intr(otm['strike']) - otm['price'], 'color': LEG_COLORS[2]})

    elif strategy_key == 'short-call-butterfly':
        itm = _find_row(data, 'ITM', 'call')
        atm = _find_row(data, 'ATM', 'call')
        otm = _find_row(data, 'OTM', 'call')
        lines.append({'label': f'Short ITM Call ({itm["price"]:,.2f})', 'payoff': itm['price'] - call_intr(itm['strike']), 'color': LEG_COLORS[0]})
        lines.append({'label': f'Long 2x ATM Call ({atm["price"]:,.2f})', 'payoff': 2 * (call_intr(atm['strike']) - atm['price']), 'color': LEG_COLORS[1]})
        lines.append({'label': f'Short OTM Call ({otm["price"]:,.2f})', 'payoff': otm['price'] - call_intr(otm['strike']), 'color': LEG_COLORS[2]})

    elif strategy_key == 'long-put-butterfly':
        itm = _find_row(data, 'ITM', 'put')
        atm = _find_row(data, 'ATM', 'put')
        otm = _find_row(data, 'OTM', 'put')
        lines.append({'label': f'Long ITM Put ({itm["price"]:,.2f})', 'payoff': put_intr(itm['strike']) - itm['price'], 'color': LEG_COLORS[0]})
        lines.append({'label': f'Short 2x ATM Put ({atm["price"]:,.2f})', 'payoff': 2 * (atm['price'] - put_intr(atm['strike'])), 'color': LEG_COLORS[1]})
        lines.append({'label': f'Long OTM Put ({otm["price"]:,.2f})', 'payoff': put_intr(otm['strike']) - otm['price'], 'color': LEG_COLORS[2]})

    elif strategy_key == 'short-put-butterfly':
        itm = _find_row(data, 'ITM', 'put')
        atm = _find_row(data, 'ATM', 'put')
        otm = _find_row(data, 'OTM', 'put')
        lines.append({'label': f'Short ITM Put ({itm["price"]:,.2f})', 'payoff': itm['price'] - put_intr(itm['strike']), 'color': LEG_COLORS[0]})
        lines.append({'label': f'Long 2x ATM Put ({atm["price"]:,.2f})', 'payoff': 2 * (put_intr(atm['strike']) - atm['price']), 'color': LEG_COLORS[1]})
        lines.append({'label': f'Short OTM Put ({otm["price"]:,.2f})', 'payoff': otm['price'] - put_intr(otm['strike']), 'color': LEG_COLORS[2]})

    # Calendar Spreads - no meaningful leg decomposition (different expiries)

    # Covered Call/Put
    elif strategy_key == 'covered-call':
        call_row = _find_row(data, 'call')
        if S0 is None:
            S0 = _get_expiration_price(data)
        lines.append({'label': 'Long Stock', 'payoff': S - S0, 'color': LEG_COLORS[0]})
        lines.append({'label': f'Short Call ({call_row["price"]:,.2f})', 'payoff': call_row['price'] - call_intr(call_row['strike']), 'color': LEG_COLORS[1]})

    elif strategy_key == 'covered-put':
        put_row = _find_row(data, 'put')
        if S0 is None:
            S0 = _get_expiration_price(data)
        lines.append({'label': 'Short Stock', 'payoff': S0 - S, 'color': LEG_COLORS[0]})
        lines.append({'label': f'Short Put ({put_row["price"]:,.2f})', 'payoff': put_row['price'] - put_intr(put_row['strike']), 'color': LEG_COLORS[1]})

    # Iron Butterflies
    elif strategy_key == 'long-iron-butterfly':
        otm_call = _find_row(data, 'OTM', 'call')
        otm_put = _find_row(data, 'OTM', 'put')
        atm_call = _find_row(data, 'ATM', 'call')
        atm_put = _find_row(data, 'ATM', 'put')
        lines.append({'label': f'Long OTM Call ({otm_call["price"]:,.2f})', 'payoff': call_intr(otm_call['strike']) - otm_call['price'], 'color': LEG_COLORS[0]})
        lines.append({'label': f'Long OTM Put ({otm_put["price"]:,.2f})', 'payoff': put_intr(otm_put['strike']) - otm_put['price'], 'color': LEG_COLORS[1]})
        lines.append({'label': f'Short ATM Call ({atm_call["price"]:,.2f})', 'payoff': atm_call['price'] - call_intr(atm_call['strike']), 'color': LEG_COLORS[2]})
        lines.append({'label': f'Short ATM Put ({atm_put["price"]:,.2f})', 'payoff': atm_put['price'] - put_intr(atm_put['strike']), 'color': LEG_COLORS[3]})

    elif strategy_key == 'short-iron-butterfly':
        otm_call = _find_row(data, 'OTM', 'call')
        otm_put = _find_row(data, 'OTM', 'put')
        atm_call = _find_row(data, 'ATM', 'call')
        atm_put = _find_row(data, 'ATM', 'put')
        lines.append({'label': f'Short OTM Call ({otm_call["price"]:,.2f})', 'payoff': otm_call['price'] - call_intr(otm_call['strike']), 'color': LEG_COLORS[0]})
        lines.append({'label': f'Short OTM Put ({otm_put["price"]:,.2f})', 'payoff': otm_put['price'] - put_intr(otm_put['strike']), 'color': LEG_COLORS[1]})
        lines.append({'label': f'Long ATM Call ({atm_call["price"]:,.2f})', 'payoff': call_intr(atm_call['strike']) - atm_call['price'], 'color': LEG_COLORS[2]})
        lines.append({'label': f'Long ATM Put ({atm_put["price"]:,.2f})', 'payoff': put_intr(atm_put['strike']) - atm_put['price'], 'color': LEG_COLORS[3]})

    # Iron Condors
    elif strategy_key == 'long-iron-condor':
        far_call = _find_row(data, 'far', 'call') or _find_row(data, 'Far-OTM', 'call')
        far_put = _find_row(data, 'far', 'put') or _find_row(data, 'Far-OTM', 'put')
        near_call = _find_row(data, 'near', 'call') or _find_row(data, 'Near-OTM', 'call')
        near_put = _find_row(data, 'near', 'put') or _find_row(data, 'Near-OTM', 'put')
        lines.append({'label': f'Long Far Call ({far_call["price"]:,.2f})', 'payoff': call_intr(far_call['strike']) - far_call['price'], 'color': LEG_COLORS[0]})
        lines.append({'label': f'Long Far Put ({far_put["price"]:,.2f})', 'payoff': put_intr(far_put['strike']) - far_put['price'], 'color': LEG_COLORS[1]})
        lines.append({'label': f'Short Near Call ({near_call["price"]:,.2f})', 'payoff': near_call['price'] - call_intr(near_call['strike']), 'color': LEG_COLORS[2]})
        lines.append({'label': f'Short Near Put ({near_put["price"]:,.2f})', 'payoff': near_put['price'] - put_intr(near_put['strike']), 'color': LEG_COLORS[3]})

    elif strategy_key == 'short-iron-condor':
        far_call = _find_row(data, 'far', 'call') or _find_row(data, 'Far-OTM', 'call')
        far_put = _find_row(data, 'far', 'put') or _find_row(data, 'Far-OTM', 'put')
        near_call = _find_row(data, 'near', 'call') or _find_row(data, 'Near-OTM', 'call')
        near_put = _find_row(data, 'near', 'put') or _find_row(data, 'Near-OTM', 'put')
        lines.append({'label': f'Short Far Call ({far_call["price"]:,.2f})', 'payoff': far_call['price'] - call_intr(far_call['strike']), 'color': LEG_COLORS[0]})
        lines.append({'label': f'Short Far Put ({far_put["price"]:,.2f})', 'payoff': far_put['price'] - put_intr(far_put['strike']), 'color': LEG_COLORS[1]})
        lines.append({'label': f'Long Near Call ({near_call["price"]:,.2f})', 'payoff': call_intr(near_call['strike']) - near_call['price'], 'color': LEG_COLORS[2]})
        lines.append({'label': f'Long Near Put ({near_put["price"]:,.2f})', 'payoff': put_intr(near_put['strike']) - near_put['price'], 'color': LEG_COLORS[3]})

    # Naked Options
    elif strategy_key == 'naked-call':
        call_row = _find_row(data, 'call')
        lines.append({'label': f'Short Call ({call_row["price"]:,.2f})', 'payoff': call_row['price'] - call_intr(call_row['strike']), 'color': LEG_COLORS[0]})

    elif strategy_key == 'naked-put':
        put_row = _find_row(data, 'put')
        lines.append({'label': f'Short Put ({put_row["price"]:,.2f})', 'payoff': put_row['price'] - put_intr(put_row['strike']), 'color': LEG_COLORS[0]})

    # Protective Options
    elif strategy_key == 'protective-call':
        call_row = _find_row(data, 'call')
        if S0 is None:
            S0 = _get_expiration_price(data)
        lines.append({'label': 'Short Stock', 'payoff': S0 - S, 'color': LEG_COLORS[0]})
        lines.append({'label': f'Long Call ({call_row["price"]:,.2f})', 'payoff': call_intr(call_row['strike']) - call_row['price'], 'color': LEG_COLORS[1]})

    elif strategy_key == 'protective-put':
        put_row = _find_row(data, 'put')
        if S0 is None:
            S0 = _get_expiration_price(data)
        lines.append({'label': 'Long Stock', 'payoff': S - S0, 'color': LEG_COLORS[0]})
        lines.append({'label': f'Long Put ({put_row["price"]:,.2f})', 'payoff': put_intr(put_row['strike']) - put_row['price'], 'color': LEG_COLORS[1]})

    elif strategy_key == 'protective-collar':
        call_row = _find_row(data, 'call')
        put_row = _find_row(data, 'put')
        if S0 is None:
            S0 = _get_expiration_price(data)
        lines.append({'label': 'Long Stock', 'payoff': S - S0, 'color': LEG_COLORS[0]})
        lines.append({'label': f'Long Put ({put_row["price"]:,.2f})', 'payoff': put_intr(put_row['strike']) - put_row['price'], 'color': LEG_COLORS[1]})
        lines.append({'label': f'Short Call ({call_row["price"]:,.2f})', 'payoff': call_row['price'] - call_intr(call_row['strike']), 'color': LEG_COLORS[2]})

    # Straddles
    elif strategy_key == 'long-straddle':
        call_row = _find_row(data, 'call')
        put_row = _find_row(data, 'put')
        lines.append({'label': f'Long Call ({call_row["price"]:,.2f})', 'payoff': call_intr(call_row['strike']) - call_row['price'], 'color': LEG_COLORS[0]})
        lines.append({'label': f'Long Put ({put_row["price"]:,.2f})', 'payoff': put_intr(put_row['strike']) - put_row['price'], 'color': LEG_COLORS[1]})

    elif strategy_key == 'short-straddle':
        call_row = _find_row(data, 'call')
        put_row = _find_row(data, 'put')
        lines.append({'label': f'Short Call ({call_row["price"]:,.2f})', 'payoff': call_row['price'] - call_intr(call_row['strike']), 'color': LEG_COLORS[0]})
        lines.append({'label': f'Short Put ({put_row["price"]:,.2f})', 'payoff': put_row['price'] - put_intr(put_row['strike']), 'color': LEG_COLORS[1]})

    # Strangles
    elif strategy_key == 'long-strangle':
        call_row = _find_row(data, 'call')
        put_row = _find_row(data, 'put')
        lines.append({'label': f'Long Call ({call_row["price"]:,.2f})', 'payoff': call_intr(call_row['strike']) - call_row['price'], 'color': LEG_COLORS[0]})
        lines.append({'label': f'Long Put ({put_row["price"]:,.2f})', 'payoff': put_intr(put_row['strike']) - put_row['price'], 'color': LEG_COLORS[1]})

    elif strategy_key == 'short-strangle':
        call_row = _find_row(data, 'call')
        put_row = _find_row(data, 'put')
        lines.append({'label': f'Short Call ({call_row["price"]:,.2f})', 'payoff': call_row['price'] - call_intr(call_row['strike']), 'color': LEG_COLORS[0]})
        lines.append({'label': f'Short Put ({put_row["price"]:,.2f})', 'payoff': put_row['price'] - put_intr(put_row['strike']), 'color': LEG_COLORS[1]})

    # Conversions / Reverse Conversions
    elif strategy_key == 'conversion':
        call_row = _find_row(data, 'call')
        put_row = _find_row(data, 'put')
        if S0 is None:
            S0 = _get_expiration_price(data)
        lines.append({'label': 'Long Stock', 'payoff': S - S0, 'color': LEG_COLORS[0]})
        lines.append({'label': f'Long Put ({put_row["price"]:,.2f})', 'payoff': put_intr(put_row['strike']) - put_row['price'], 'color': LEG_COLORS[1]})
        lines.append({'label': f'Short Call ({call_row["price"]:,.2f})', 'payoff': call_row['price'] - call_intr(call_row['strike']), 'color': LEG_COLORS[2]})

    elif strategy_key == 'reverse-conversion':
        call_row = _find_row(data, 'call')
        put_row = _find_row(data, 'put')
        if S0 is None:
            S0 = _get_expiration_price(data)
        lines.append({'label': 'Short Stock', 'payoff': S0 - S, 'color': LEG_COLORS[0]})
        lines.append({'label': f'Long Call ({call_row["price"]:,.2f})', 'payoff': call_intr(call_row['strike']) - call_row['price'], 'color': LEG_COLORS[1]})
        lines.append({'label': f'Short Put ({put_row["price"]:,.2f})', 'payoff': put_row['price'] - put_intr(put_row['strike']), 'color': LEG_COLORS[2]})

    # Box Spreads
    elif strategy_key == 'long-box-spread':
        itm_call = _find_row(data, 'ITM', 'call')
        otm_call = _find_row(data, 'OTM', 'call')
        itm_put = _find_row(data, 'ITM', 'put')
        otm_put = _find_row(data, 'OTM', 'put')
        lines.append({'label': f'Long ITM Call ({itm_call["price"]:,.2f})', 'payoff': call_intr(itm_call['strike']) - itm_call['price'], 'color': LEG_COLORS[0]})
        lines.append({'label': f'Short OTM Call ({otm_call["price"]:,.2f})', 'payoff': otm_call['price'] - call_intr(otm_call['strike']), 'color': LEG_COLORS[1]})
        lines.append({'label': f'Long ITM Put ({itm_put["price"]:,.2f})', 'payoff': put_intr(itm_put['strike']) - itm_put['price'], 'color': LEG_COLORS[2]})
        lines.append({'label': f'Short OTM Put ({otm_put["price"]:,.2f})', 'payoff': otm_put['price'] - put_intr(otm_put['strike']), 'color': LEG_COLORS[3]})

    elif strategy_key == 'short-box-spread':
        itm_call = _find_row(data, 'ITM', 'call')
        otm_call = _find_row(data, 'OTM', 'call')
        itm_put = _find_row(data, 'ITM', 'put')
        otm_put = _find_row(data, 'OTM', 'put')
        lines.append({'label': f'Short ITM Call ({itm_call["price"]:,.2f})', 'payoff': itm_call['price'] - call_intr(itm_call['strike']), 'color': LEG_COLORS[0]})
        lines.append({'label': f'Long OTM Call ({otm_call["price"]:,.2f})', 'payoff': call_intr(otm_call['strike']) - otm_call['price'], 'color': LEG_COLORS[1]})
        lines.append({'label': f'Short ITM Put ({itm_put["price"]:,.2f})', 'payoff': itm_put['price'] - put_intr(itm_put['strike']), 'color': LEG_COLORS[2]})
        lines.append({'label': f'Long OTM Put ({otm_put["price"]:,.2f})', 'payoff': put_intr(otm_put['strike']) - otm_put['price'], 'color': LEG_COLORS[3]})

    # Jelly Rolls - no meaningful single-expiry leg decomposition

    # Ladders
    elif strategy_key == 'bear-call-ladder':
        low = _find_row(data, 'lower', 'call') or _find_row(data, 'low', 'call')
        mid = _find_row(data, 'middle', 'call') or _find_row(data, 'mid', 'call')
        high = _find_row(data, 'higher', 'call') or _find_row(data, 'high', 'call')
        lines.append({'label': f'Short Lower Call ({low["price"]:,.2f})', 'payoff': low['price'] - call_intr(low['strike']), 'color': LEG_COLORS[0]})
        lines.append({'label': f'Long Middle Call ({mid["price"]:,.2f})', 'payoff': call_intr(mid['strike']) - mid['price'], 'color': LEG_COLORS[1]})
        lines.append({'label': f'Long Higher Call ({high["price"]:,.2f})', 'payoff': call_intr(high['strike']) - high['price'], 'color': LEG_COLORS[2]})

    elif strategy_key == 'bear-put-ladder':
        low = _find_row(data, 'lower', 'put') or _find_row(data, 'low', 'put')
        mid = _find_row(data, 'middle', 'put') or _find_row(data, 'mid', 'put')
        high = _find_row(data, 'higher', 'put') or _find_row(data, 'high', 'put')
        lines.append({'label': f'Long Higher Put ({high["price"]:,.2f})', 'payoff': put_intr(high['strike']) - high['price'], 'color': LEG_COLORS[0]})
        lines.append({'label': f'Short Middle Put ({mid["price"]:,.2f})', 'payoff': mid['price'] - put_intr(mid['strike']), 'color': LEG_COLORS[1]})
        lines.append({'label': f'Short Lower Put ({low["price"]:,.2f})', 'payoff': low['price'] - put_intr(low['strike']), 'color': LEG_COLORS[2]})

    elif strategy_key == 'bull-call-ladder':
        low = _find_row(data, 'lower', 'call') or _find_row(data, 'low', 'call')
        mid = _find_row(data, 'middle', 'call') or _find_row(data, 'mid', 'call')
        high = _find_row(data, 'higher', 'call') or _find_row(data, 'high', 'call')
        lines.append({'label': f'Long Lower Call ({low["price"]:,.2f})', 'payoff': call_intr(low['strike']) - low['price'], 'color': LEG_COLORS[0]})
        lines.append({'label': f'Short Middle Call ({mid["price"]:,.2f})', 'payoff': mid['price'] - call_intr(mid['strike']), 'color': LEG_COLORS[1]})
        lines.append({'label': f'Short Higher Call ({high["price"]:,.2f})', 'payoff': high['price'] - call_intr(high['strike']), 'color': LEG_COLORS[2]})

    elif strategy_key == 'bull-put-ladder':
        low = _find_row(data, 'lower', 'put') or _find_row(data, 'low', 'put')
        mid = _find_row(data, 'middle', 'put') or _find_row(data, 'mid', 'put')
        high = _find_row(data, 'higher', 'put') or _find_row(data, 'high', 'put')
        lines.append({'label': f'Short Higher Put ({high["price"]:,.2f})', 'payoff': high['price'] - put_intr(high['strike']), 'color': LEG_COLORS[0]})
        lines.append({'label': f'Long Middle Put ({mid["price"]:,.2f})', 'payoff': put_intr(mid['strike']) - mid['price'], 'color': LEG_COLORS[1]})
        lines.append({'label': f'Long Lower Put ({low["price"]:,.2f})', 'payoff': put_intr(low['strike']) - low['price'], 'color': LEG_COLORS[2]})

    # Backspreads
    elif strategy_key == 'long-call-backspread':
        low = _find_row(data, 'lower', 'call') or _find_row(data, 'low', 'call')
        high = _find_row(data, 'higher', 'call') or _find_row(data, 'high', 'call')
        lines.append({'label': f'Short Lower Call ({low["price"]:,.2f})', 'payoff': low['price'] - call_intr(low['strike']), 'color': LEG_COLORS[0]})
        lines.append({'label': f'Long 2x Higher Call ({high["price"]:,.2f})', 'payoff': 2 * (call_intr(high['strike']) - high['price']), 'color': LEG_COLORS[1]})

    elif strategy_key == 'short-call-backspread':
        low = _find_row(data, 'lower', 'call') or _find_row(data, 'low', 'call')
        high = _find_row(data, 'higher', 'call') or _find_row(data, 'high', 'call')
        lines.append({'label': f'Long Lower Call ({low["price"]:,.2f})', 'payoff': call_intr(low['strike']) - low['price'], 'color': LEG_COLORS[0]})
        lines.append({'label': f'Short 2x Higher Call ({high["price"]:,.2f})', 'payoff': 2 * (high['price'] - call_intr(high['strike'])), 'color': LEG_COLORS[1]})

    elif strategy_key == 'long-put-backspread':
        low = _find_row(data, 'lower', 'put') or _find_row(data, 'low', 'put')
        high = _find_row(data, 'higher', 'put') or _find_row(data, 'high', 'put')
        lines.append({'label': f'Long 2x Lower Put ({low["price"]:,.2f})', 'payoff': 2 * (put_intr(low['strike']) - low['price']), 'color': LEG_COLORS[0]})
        lines.append({'label': f'Short Higher Put ({high["price"]:,.2f})', 'payoff': high['price'] - put_intr(high['strike']), 'color': LEG_COLORS[1]})

    elif strategy_key == 'short-put-backspread':
        low = _find_row(data, 'lower', 'put') or _find_row(data, 'low', 'put')
        high = _find_row(data, 'higher', 'put') or _find_row(data, 'high', 'put')
        lines.append({'label': f'Short 2x Lower Put ({low["price"]:,.2f})', 'payoff': 2 * (low['price'] - put_intr(low['strike'])), 'color': LEG_COLORS[0]})
        lines.append({'label': f'Long Higher Put ({high["price"]:,.2f})', 'payoff': put_intr(high['strike']) - high['price'], 'color': LEG_COLORS[1]})

    return lines


def _compute_example_pl(strategy_key, data, S_range):
    """Compute the example P/L at the expiration price from the table.

    Returns dict {'S_T': float, 'pl': float} or None if not computable.
    """
    S_T = _get_expiration_price(data)
    if S_T is None:
        return None

    # Count option legs (rows without "underlying") for fee calculation
    option_legs = [r for r in data if 'underlying' not in r['asset'].lower()]
    num_contracts = len(option_legs)
    multiplier = 100
    fees = num_contracts  # $1 per contract

    # Compute per-share payoff at S_T using the strategy payoff formula
    S_point = np.array([S_T])
    try:
        payoff_arr, _, _, _, _ = _compute_payoff(strategy_key, data, S_point)
        per_share = payoff_arr[0]
    except Exception:
        return None

    pl = round(per_share * multiplier - fees)
    return {'S_T': S_T, 'pl': pl}


def _render_payoff_chart(strategy_name, S, payoff, strikes, premiums,
                         breakevens, S0, bg_path, output_path,
                         leg_lines=None, example_point=None):
    """Render the payoff chart composited onto the background image."""

    CHART_COLOR = '#4A90D9'
    PROFIT_COLOR = '#2ECC71'
    LOSS_COLOR = '#E74C3C'
    STRIKE_COLOR = '#8E44AD'
    BREAKEVEN_COLOR = '#E67E22'
    STOCK_PRICE_COLOR = '#34495E'
    ZERO_LINE_COLOR = '#7F8C8D'
    LINE_WIDTH = 2.5
    LEG_LINE_WIDTH = 1.5
    FONT_SIZE_TITLE = 16
    FONT_SIZE_LABEL = 11
    FONT_SIZE_ANNOTATION = 9
    FONT_SIZE_TICK = 9

    LEG_COLORS = ['#3498DB', '#E67E22', '#27AE60', '#E74C3C', '#9B59B6', '#1ABC9C']

    fig_w, fig_h = 1200 / 96, 630 / 96
    fig, ax = plt.subplots(figsize=(fig_w, fig_h), dpi=96)
    fig.subplots_adjust(left=0.10, right=0.92, top=0.88, bottom=0.14)

    # Plot individual leg lines first (behind the combined payoff)
    # Add $ to premium values and compute net cost for combined label
    net_cost = 0.0
    has_premiums = False
    if leg_lines:
        for i, leg in enumerate(leg_lines):
            color = leg.get('color', LEG_COLORS[i % len(LEG_COLORS)])
            label = leg['label']
            # Add $ to premium in parentheses: "Short ITM Call (13.80)" -> "Short ITM Call ($13.80)"
            import re as _re
            m = _re.search(r'\((\d[\d,.]*)\)$', label)
            if m:
                premium_val = float(m.group(1).replace(',', ''))
                label = label[:m.start()] + f'(${m.group(1)})' + label[m.end():]
                has_premiums = True
                # Long = debit (negative), Short = credit (positive)
                is_long = label.lower().startswith('long')
                # Handle "2x" multiplier in label
                multiplier = 1
                if '2x' in label:
                    multiplier = 2
                if is_long:
                    net_cost -= premium_val * multiplier
                else:
                    net_cost += premium_val * multiplier
            ax.plot(S, leg['payoff'], color=color, linewidth=LEG_LINE_WIDTH,
                    linestyle='--', alpha=0.7, zorder=4, label=label)

    # Plot combined payoff line with net cost/credit in label
    if has_premiums:
        sign = '' if net_cost < 0 else '+'
        combined_label = f'{strategy_name} (net {sign}${net_cost:,.2f})'
    else:
        combined_label = strategy_name
    ax.plot(S, payoff, color=CHART_COLOR, linewidth=LINE_WIDTH, zorder=5,
            label=combined_label)

    # Fill profit/loss regions for the combined payoff
    ax.fill_between(S, payoff, 0, where=(payoff >= 0),
                    color=PROFIT_COLOR, alpha=0.15, interpolate=True, zorder=2)
    ax.fill_between(S, payoff, 0, where=(payoff < 0),
                    color=LOSS_COLOR, alpha=0.15, interpolate=True, zorder=2)

    # Zero line
    ax.axhline(y=0, color=ZERO_LINE_COLOR, linewidth=0.8, linestyle='-', zorder=3)

    # Set axis limits early so annotations can reference them
    # Center y-axis on 0, round limits to multiples of 5
    import math
    y_min_auto, y_max_auto = ax.get_ylim()
    y_abs_max = max(abs(y_min_auto), abs(y_max_auto)) * 1.25
    y_abs_max = math.ceil(y_abs_max / 5) * 5
    ax.set_ylim(-y_abs_max, y_abs_max)

    # Strike price annotations (vertical dashed lines)
    # For 3+ strikes, stagger: middle on top row, outer on bottom row
    strike_items = list(strikes.items())
    num_strikes = len(strike_items)
    if num_strikes >= 3:
        sorted_strikes = sorted(strike_items, key=lambda x: x[1])
        strike_rows = {}
        for i, (label, _) in enumerate(sorted_strikes):
            if num_strikes == 3:
                strike_rows[label] = 0 if i == 1 else 1
            elif num_strikes == 4:
                strike_rows[label] = 0 if i in (1, 2) else 1
            else:
                strike_rows[label] = i % 2
    else:
        strike_rows = {label: 0 for label, _ in strike_items}

    # Position strike labels anchored at y-axis max (a nice round number after limits are set)
    y_strike_anchor = ax.get_ylim()[1]

    for label, k_val in strikes.items():
        ax.axvline(x=k_val, color=STRIKE_COLOR, linewidth=1.0, linestyle='--',
                   alpha=0.7, zorder=4)
        row = strike_rows.get(label, 0)
        # Top row: just below y_max; bottom row: 35pt further down
        y_offset = -8 if row == 0 else -43
        ax.annotate(f'{label}\n{k_val:,.2f}',
                    xy=(k_val, y_strike_anchor), xytext=(0, y_offset),
                    textcoords='offset points', ha='center', va='top',
                    fontsize=FONT_SIZE_ANNOTATION, color=STRIKE_COLOR,
                    fontweight='bold', zorder=8,
                    bbox=dict(boxstyle='round,pad=0.3', facecolor='white',
                              edgecolor=STRIKE_COLOR, alpha=1.0))

    # X-axis tick spacing: use $5 increments when fewer than 10 ticks would result
    from matplotlib.ticker import MultipleLocator
    x_range = S[-1] - S[0]
    if x_range / 5 <= 15:
        ax.xaxis.set_major_locator(MultipleLocator(5))

    # S_T marker (underlying price at expiration)
    if S0 is not None:
        ax.axvline(x=S0, color=STOCK_PRICE_COLOR, linewidth=1.0,
                   linestyle=':', alpha=0.6, zorder=4)
        y_bottom = ax.get_ylim()[0]
        ax.annotate(f'S\u1d1b={S0:,.2f}',
                    xy=(S0, y_bottom), xytext=(0, 8),
                    textcoords='offset points', ha='center', va='bottom',
                    fontsize=FONT_SIZE_ANNOTATION, color=STOCK_PRICE_COLOR,
                    fontweight='bold', zorder=8,
                    bbox=dict(boxstyle='round,pad=0.3', facecolor='white',
                              edgecolor=STOCK_PRICE_COLOR, alpha=1.0))

    # Breakeven annotations
    for i, be_val in enumerate(breakevens):
        if S[0] <= be_val <= S[-1]:
            ax.plot(be_val, 0, 'o', color=BREAKEVEN_COLOR, markersize=7,
                    zorder=6, markeredgecolor='white', markeredgewidth=1.0)
            ax.annotate(f'BE {be_val:,.2f}',
                        xy=(be_val, 0), xytext=(0, 14),
                        textcoords='offset points', ha='center', va='bottom',
                        fontsize=FONT_SIZE_ANNOTATION, color=BREAKEVEN_COLOR,
                        fontweight='bold', zorder=8,
                        bbox=dict(boxstyle='round,pad=0.3', facecolor='white',
                                  edgecolor=BREAKEVEN_COLOR, alpha=1.0))

    # Example outcome point
    if example_point is not None:
        s_t = example_point['S_T']
        pl = example_point['pl']
        payoff_at_st = np.interp(s_t, S, payoff)
        EXAMPLE_COLOR = '#16A085'
        ax.plot(s_t, payoff_at_st, 's', color=EXAMPLE_COLOR, markersize=9,
                zorder=7, markeredgecolor='white', markeredgewidth=1.5)
        sign = '+' if pl >= 0 else ''
        ax.annotate(f'P/L: {sign}{pl:,.0f}',
                    xy=(s_t, payoff_at_st), xytext=(12, -18),
                    textcoords='offset points', ha='left', va='top',
                    fontsize=FONT_SIZE_ANNOTATION, color=EXAMPLE_COLOR,
                    fontweight='bold', zorder=8,
                    bbox=dict(boxstyle='round,pad=0.3', facecolor='white',
                              edgecolor=EXAMPLE_COLOR, alpha=1.0),
                    arrowprops=dict(arrowstyle='->', color=EXAMPLE_COLOR,
                                    lw=1.5))

    # Axes labels
    ax.set_xlabel('Stock Price ($)', fontsize=FONT_SIZE_LABEL,
                  fontweight='bold', labelpad=8)
    ax.set_ylabel('P/L ($)', fontsize=FONT_SIZE_LABEL,
                  fontweight='bold', labelpad=8)

    # Title
    ax.set_title(strategy_name, fontsize=FONT_SIZE_TITLE, fontweight='bold',
                 pad=12, color='#2C3E50')

    # Legend placement using sub-quadrant grid
    # Divide chart into 8x4 grid (8 columns, 4 rows):
    #   abcdefgh  (top row)
    #   ijklmnop
    #   qrstuvwx
    #   12345678  (bottom row)
    # Collect annotation positions, then try: gh (upper-right), ab (upper-left),
    # 12 (lower-left), 78 (lower-right) — place in the first region with no conflicts.
    if leg_lines:
        x_lim = ax.get_xlim()
        y_lim = ax.get_ylim()
        x_range = x_lim[1] - x_lim[0]
        y_range = y_lim[1] - y_lim[0]

        # Collect all annotation points as (x, y) normalized to [0,1]
        annotation_points = []
        # Strike annotations (near top of chart)
        for k_val in strikes.values():
            annotation_points.append(((k_val - x_lim[0]) / x_range, 0.85))
        # S_T annotation (near bottom of chart)
        if S0 is not None:
            annotation_points.append(((S0 - x_lim[0]) / x_range, 0.1))
        # Breakeven annotations (near y=0, which is at 0.5 since y is centered)
        for be_val in breakevens:
            if x_lim[0] <= be_val <= x_lim[1]:
                annotation_points.append(((be_val - x_lim[0]) / x_range, 0.55))
        # P/L annotation (near S_T, slightly above/below)
        if example_point is not None:
            s_t = example_point['S_T']
            annotation_points.append(((s_t - x_lim[0]) / x_range, 0.4))

        def region_has_conflict(x_lo, x_hi, y_lo, y_hi):
            """Check if any annotation point falls in this normalized region."""
            for px, py in annotation_points:
                if x_lo <= px <= x_hi and y_lo <= py <= y_hi:
                    return True
            return False

        # Try regions in preference order: upper-right, upper-left, lower-left, lower-right
        # Each region covers 2/8 columns and 1/4 rows
        if not region_has_conflict(0.75, 1.0, 0.75, 1.0):
            best_loc = 'upper right'
        elif not region_has_conflict(0.0, 0.25, 0.75, 1.0):
            best_loc = 'upper left'
        elif not region_has_conflict(0.0, 0.25, 0.0, 0.25):
            best_loc = 'lower left'
        else:
            best_loc = 'lower right'

        ax.legend(loc=best_loc, fontsize=FONT_SIZE_ANNOTATION,
                  framealpha=0.95, edgecolor='#BDC3C7')

    # Tick formatting
    ax.tick_params(axis='both', labelsize=FONT_SIZE_TICK)
    ax.xaxis.set_major_formatter(FuncFormatter(lambda x, p: f'{x:,.0f}'))
    ax.yaxis.set_major_formatter(FuncFormatter(lambda x, p: f'{x:,.0f}'))

    # Grid
    ax.grid(True, alpha=0.3, linestyle='-', linewidth=0.5)
    ax.set_axisbelow(True)

    # Remove top and right spines
    ax.spines['top'].set_visible(False)
    ax.spines['right'].set_visible(False)

    # Make the background transparent so the template shows through
    fig.patch.set_alpha(0)
    ax.set_facecolor('none')

    # Save chart to a bytes buffer
    buf = BytesIO()
    fig.savefig(buf, format='png', dpi=96, transparent=True,
                bbox_inches='tight', pad_inches=0)
    plt.close(fig)
    buf.seek(0)

    # Composite onto the background template
    bg = Image.open(bg_path).convert('RGBA')
    bg = bg.resize((1200, 630), Image.LANCZOS)
    chart_img = Image.open(buf).convert('RGBA')

    # Center the chart on the background
    cw, ch = chart_img.size
    x_offset = (1200 - cw) // 2
    y_offset = (630 - ch) // 2

    bg.paste(chart_img, (x_offset, y_offset), chart_img)

    final = bg.convert('RGB')
    Path(output_path).parent.mkdir(parents=True, exist_ok=True)
    final.save(output_path, 'PNG', dpi=(96, 96))
    final.close()
    bg.close()
    chart_img.close()
    buf.close()
    print(f'Saved: {output_path}')


def _generate_option_strategy_payoff_charts(location):
    """Generate annotated payoff chart images for all 41 option strategies."""
    bg_path = f'{location}meta-image-background.png'
    if not os.path.exists(bg_path):
        print(f'Warning: background template not found at {bg_path}')
        return

    strategies_base = OPTION_STRATEGIES_DIR
    if not os.path.exists(strategies_base):
        print(f'Warning: strategies directory not found at {strategies_base}')
        return

    count = 0
    for dir_name in sorted(os.listdir(strategies_base)):
        dir_path = os.path.join(strategies_base, dir_name)
        if not os.path.isdir(dir_path):
            continue

        example_path = os.path.join(dir_path, '04 Example.html')
        if not os.path.exists(example_path):
            print(f'Skipping {dir_name}: no 04 Example.html')
            continue

        strategy_key = _classify_strategy(dir_name)
        slug = _get_slug(dir_name)
        strategy_name = ' '.join(dir_name.split(' ')[1:])

        try:
            data = _parse_example_table(example_path)
            if not data:
                print(f'Warning: empty table in {example_path}')
                continue

            # Determine S range from strike prices and underlying price
            all_strikes = [r.get('strike') for r in data if r.get('strike') is not None]

            if not all_strikes:
                print(f'Warning: no strikes found in {example_path}')
                continue

            # Set x-axis range: center on the middle strike, extend to cover
            # all strikes, breakevens, and S_T with 50% extra padding
            min_k = min(all_strikes)
            max_k = max(all_strikes)
            mid_k = (min_k + max_k) / 2
            S_exp = _get_expiration_price(data)
            S0_val = _get_underlying_price(data)

            # Collect all key values that must be visible
            key_values = list(all_strikes)
            if S_exp is not None:
                key_values.append(S_exp)
            if S0_val is not None:
                key_values.append(S0_val)

            # Compute half-range: max distance from mid_k to any key value, * 1.5
            # Pre-round to multiples of 5 so the data fills the entire axis
            import math
            max_dist = max(abs(v - mid_k) for v in key_values)
            if max_dist == 0:
                max_dist = mid_k * 0.1
            half_range = max_dist * 1.5
            S_min = math.floor((mid_k - half_range) / 5) * 5
            S_max = math.ceil((mid_k + half_range) / 5) * 5
            S = np.linspace(max(0, S_min), S_max, 500)

            payoff, strikes_dict, premiums_dict, breakevens, S0_computed = \
                _compute_payoff(strategy_key, data, S)

            # Widen range if breakevens fall outside
            all_visible = list(key_values) + [b for b in breakevens if b is not None]
            max_dist2 = max(abs(v - mid_k) for v in all_visible)
            if max_dist2 > half_range:
                half_range = max_dist2 * 1.5
                S_min = math.floor((mid_k - half_range) / 5) * 5
                S_max = math.ceil((mid_k + half_range) / 5) * 5
                S = np.linspace(max(0, S_min), S_max, 500)
                payoff, strikes_dict, premiums_dict, breakevens, S0_computed = \
                    _compute_payoff(strategy_key, data, S)

            leg_lines = _build_leg_lines(strategy_key, data, S)
            example_point = _compute_example_pl(strategy_key, data, S)

            output_path = os.path.join(
                location,
                f'writing-algorithms/trading-and-orders/option-strategies/{slug}.png'
            )

            _render_payoff_chart(
                strategy_name, S, payoff, strikes_dict, premiums_dict,
                breakevens, S0_computed, bg_path, output_path,
                leg_lines=leg_lines if leg_lines else None,
                example_point=example_point
            )
            count += 1

        except Exception as e:
            print(f'Error generating chart for {dir_name}: {e}')
            import traceback
            traceback.print_exc()

    print(f'{count} option strategy payoff charts generated')


if __name__ == '__main__':
    counter = 0
    now = dt.now()
    location = f"Resources/social/"
    fonts = [ ImageFont.FreeTypeFont(f'{location}Inter-Bold.ttf', size) for size in [ 62, 50, 42 ] ]
    missing_metadata = []

    for product in PRODUCTS:
        name = '-'.join(product.lower().split(' ')[1:])
        template = f"{location}docs-meta-{name}.png"
        if not os.path.exists(template):
            continue

        image_generator = ImageGenerator(fonts, template)
        for dir, subdirs, files in os.walk(product):
            if 'metadata.json' not in files:
                # metadata file should only be a must in the tree-leaf dir
                if not subdirs:
                    missing_metadata.append(dir)
                continue

            lines = [' '.join(line.split(' ')[1:]) for line in dir.split(os.path.sep)]

            outputfile = '/'.join([line.lower().replace(' ','-') for line in lines])
            url = f'https://cdn.quantconnect.com/docs/i/{outputfile}.png'
            outputfile = Path(location + outputfile)
            outputfile.parent.mkdir(parents=True, exist_ok=True)

            image_generator.AddTextToImage(lines[1:], outputfile)
            counter += 1

            with open(f"{dir}/metadata.json", mode='r') as fp:
                lines = fp.readlines()
                line = next((x for x in lines if "og:image" in x), None)
                if not line:
                    continue
                end = line.index('og:image')-1
                lines[lines.index(line)] = f'{line[:end]}"og:image": "{url}"\n'

            with open(f"{dir}/metadata.json", mode='w') as fp:
                fp.writelines(lines)

    # Generate LEAN CLI cheat sheet after all SEO images (overwrites lean-cli.png)
    _generate_lean_cli_cheat_sheet(location)

    # Generate option strategy payoff charts (overwrites text-based SEO images)
    _generate_option_strategy_payoff_charts(location)

    print(f'{counter} images generated in {dt.now()-now}')
    if missing_metadata:
        print(f"Found {len(missing_metadata)} missing metatdata.json files in: [" + "\n" + "\n".join(str(x) for x in missing_metadata) + "\n]")