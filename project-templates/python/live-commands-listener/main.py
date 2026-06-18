# region imports
from AlgorithmImports import *
# endregion


class CommandPauseEmaCrossAlgorithm(QCAlgorithm):
    _paused = False

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)

        # Request SPY at minute resolution so on_data (and the pause check) fires every minute
        self._spy = self.add_equity("SPY", Resolution.MINUTE).symbol

        # Enable automatic indicator warm-up
        # automatic_indicator_warm_up only supports automatic indicators, not manual indicators.
        self.settings.automatic_indicator_warm_up = True

        # Create EMA indicators for trend detection
        self._ema_fast = self.ema(self._spy, 50, Resolution.DAILY)
        # Alternatively, use a manual indicator.
        # self._ema_fast = ExponentialMovingAverage(50)
        # self.warm_up_indicator(self._spy, self._ema_fast, Resolution.DAILY)
        # self.register_indicator(self._spy, self._ema_fast, Resolution.DAILY)
        self._ema_slow = self.ema(self._spy, 200, Resolution.DAILY)
        # Alternatively, use a manual indicator.
        # self._ema_slow = ExponentialMovingAverage(200)
        # self.warm_up_indicator(self._spy, self._ema_slow, Resolution.DAILY)
        # self.register_indicator(self._spy, self._ema_slow, Resolution.DAILY)

        # Restore pause state from Object Store so the flag survives restarts
        if self.object_store.contains_key("paused"):
            stored = self.object_store.read("paused")
            if stored:
                self._paused = stored.strip().lower() == "true"
                self.log(f"Restored pause state: paused={self._paused}")

    def on_data(self, slice: Slice) -> None:
        # Skip trading while paused
        if self._paused:
            return

        # Wait for indicators to warm up
        if not self._ema_fast.is_ready or not self._ema_slow.is_ready:
            return

        fast = self._ema_fast.current.value
        slow = self._ema_slow.current.value

        # EMA cross trend-following strategy
        if fast > slow and not self.portfolio[self._spy].is_long:
            self.set_holdings(self._spy, 1.0)
            self.log(f"Buy SPY at {slice.time}: EMA50={fast:.2f} > EMA200={slow:.2f}")
        elif fast < slow and not self.portfolio[self._spy].is_short:
            self.set_holdings(self._spy, -1.0)
            self.log(f"Short SPY at {slice.time}: EMA50={fast:.2f} < EMA200={slow:.2f}")

    def on_command(self, data: DynamicData) -> Optional[bool]:
        # Handles pause/resume payload sent via REST or LEAN CLI:
        # {"command": "pause", "paused": true|false}
        # Going through on_command (instead of a Command subclass) lets us
        # mutate algorithm-private state like self._paused directly.
        # Sender-script docs:
        # https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/commands#06-Send-Commands-by-API
        if data.command != "pause":
            return False
        self._paused = bool(data.paused)
        self.object_store.save("paused", "true" if self._paused else "false")
        self.log(f"on_command pause: paused={self._paused}")
        return True
