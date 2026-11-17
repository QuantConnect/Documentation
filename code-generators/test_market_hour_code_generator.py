"""Tests for the market-hours exception inheritance used by market-hour-code-generator.py.

Usage:
    python -m unittest discover -s code-generators -p "test_market_hour*.py"
"""

import unittest

from _code_generation_helpers import resolve_market_hours_exceptions


def entry(time_zone, holidays=None, early_closes=None, late_opens=None):
    return {
        'exchangeTimeZone': time_zone,
        'holidays': holidays or [],
        'earlyCloses': early_closes or {},
        'lateOpens': late_opens or {},
    }


class ResolveMarketHoursExceptionsTests(unittest.TestCase):
    def test_named_entry_inherits_wildcard_in_its_time_zone(self):
        # Regresses #2894: Index-usa-NDX showed no early closes.
        resolved = resolve_market_hours_exceptions({
            'Index-usa-[*]': entry('America/Chicago', ['11/27/2025'], {'11/28/2025': '12:00:00'}, {'12/26/2025': '8:15:00'}),
            'Index-usa-NDX': entry('America/New_York'),
        })
        self.assertEqual(resolved['Index-usa-NDX'], {
            'holidays': ['11/27/2025'],
            'earlyCloses': {'11/28/2025': '13:00:00'},
            'lateOpens': {'12/26/2025': '09:15:00'},
        })
        self.assertEqual(resolved['Index-usa-[*]']['earlyCloses'], {'11/28/2025': '12:00:00'})

    def test_named_entry_overrides_wildcard(self):
        resolved = resolve_market_hours_exceptions({
            'Future-cme-[*]': entry('America/Chicago', ['1/20/2025', '12/25/2025'], {'11/28/2025': '12:00:00', '12/24/2025': '12:00:00'}),
            'Future-cme-ES': entry('America/Chicago', ['7/4/2025'], {'1/20/2025': '12:00:00', '11/28/2025': '12:15:00'}),
        })
        es = resolved['Future-cme-ES']
        # A wildcard holiday the entry trades on becomes a bank holiday, which the pages don't show
        self.assertCountEqual(es['holidays'], ['7/4/2025', '12/25/2025'])
        self.assertEqual(es['earlyCloses'], {'1/20/2025': '12:00:00', '11/28/2025': '12:15:00', '12/24/2025': '12:00:00'})

    def test_option_inherits_underlying_in_its_time_zone(self):
        # Options are listed first to check they resolve after their underlying.
        resolved = resolve_market_hours_exceptions({
            'IndexOption-usa-[*]': entry('America/Chicago'),
            'IndexOption-usa-NDXP': entry('America/Chicago'),
            'Option-usa-[*]': entry('America/Chicago'),
            'Equity-usa-[*]': entry('America/New_York', ['12/25/2025'], {'12/24/2025': '13:00:00'}),
            'Index-usa-[*]': entry('America/New_York'),
            'Index-usa-NDX': entry('America/New_York', ['11/27/2025'], {'11/28/2025': '13:00:00'}),
        })
        self.assertEqual(resolved['IndexOption-usa-NDXP'], {
            'holidays': ['11/27/2025'],
            'earlyCloses': {'11/28/2025': '12:00:00'},
            'lateOpens': {},
        })
        self.assertEqual(resolved['Option-usa-[*]'], {
            'holidays': ['12/25/2025'],
            'earlyCloses': {'12/24/2025': '12:00:00'},
            'lateOpens': {},
        })

    def test_wildcard_listed_after_named_entry_is_not_inherited(self):
        # Matches LEAN, which resolves non-option entries in file order.
        resolved = resolve_market_hours_exceptions({
            'Index-eurex-DAX': entry('Europe/Berlin'),
            'Index-eurex-[*]': entry('Europe/Berlin', ['12/25/2025']),
        })
        self.assertEqual(resolved['Index-eurex-DAX']['holidays'], [])


if __name__ == '__main__':
    unittest.main()
