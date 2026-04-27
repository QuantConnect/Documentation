---
name: notifications
description: Use when adding or reviewing live-trading `Notify.*` calls (Email, SMS, Telegram, Web/Webhook, FTP/SFTP) in a QuantConnect/LEAN algorithm. Notifications run in QuantConnect Cloud live trading only — backtests, local LEAN, and LEAN CLI live deployments don't have them, so guard call sites with `live_mode`. Covers per-channel limits (10 KB email body, 1,600-char SMS, 300 s webhook timeout, default `attachment.txt` filename), the tiered hourly free quota with paid overage (SMS always billed per message regardless of tier), the rule that raw subscribed-dataset content can't be sent (terms-of-use), the Discord webhook content-key JSON envelope, the Telegram group-ID + bot-token + UTF-32 emoji rules, that *receiving* messages is the live-commands skill, that *shipping portfolio targets* to a fund/platform belongs in the custom-signal-export skill rather than `notify.web`, and that `notify.*` belongs in event handlers, not per-bar `on_data`.
---

# Notifications in QuantConnect / LEAN

Notifications send messages **out** of a live-trading algorithm — to email, SMS, Telegram, an HTTP endpoint, or an (S)FTP server. They are different from commands (see the live-commands skill), which deliver messages **in** to the algorithm.

## Critical: Cloud live trading only

Notifications are wired up exclusively in QuantConnect Cloud live trading. They are no-ops in **backtests**, **local LEAN**, and **LEAN CLI live deployments**. Guard every call with `self.live_mode` so the call sites are obvious and no companion logging fires in backtests:

```python
if self.live_mode:
    self.notify.email("ops@example.com", "Order filled", details)
```

## Wrap repeated calls in a helper

When the same channel is used in more than one place, route every call through a private helper so the address/phone/group-ID/token/filename and the `live_mode` gate live in **one** place. Lift the recipient/credentials to **module-level constants** at the top of the file — the user reads them as config and edits them in one place.

```python
OPS_EMAIL = "ops@example.com"

class MyAlgorithm(QCAlgorithm):

    def _notify_email(self, subject: str, message: str) -> None:
        if self.live_mode:
            self.notify.email(OPS_EMAIL, subject, message)
```

Apply the same pattern to other channels — `_notify_sms`, `_notify_telegram`, `_notify_web`, `_notify_ftp`. One-off notifications can stay inline.

## The five channels

```
notify.email(address, subject, message, data=None, headers=None)
notify.sms(phone_number, message)
notify.telegram(id, message, token=None)
notify.web(address, data, headers=None)
notify.ftp(hostname, username, password, file_path, file_content, port=None)
notify.sftp(hostname, username, password|private_key + private_key_passphrase, file_path, file_content, port=None)
```

| Channel | Body limit |
| --- | --- |
| Email | 10 KB body, optional named attachment |
| SMS | 1,600 chars; phone must be E.164 (`+1...`) |
| Telegram | Plain text via bot in a group |
| Web (HTTP POST) | 300 s response timeout on the receiver |
| FTP / SFTP | Sends file content under `file_path` |

### Hourly free quota by tier

| Tier | Notifications per hour |
| --- | --- |
| Free | N/A |
| Quant Researcher | 20 |
| Team | 60 |
| Trading Firm | 240 |
| Institution | 3,600 |

The hourly quota covers email, FTP, Telegram, and webhook combined; overage costs 1 QCC per notification. **SMS is excluded** and always billed per message regardless of tier (1 QCC US/CA, 10 QCC international).

## Critical: Don't fire per-bar — fire on events

Putting `notify.*` inside `on_data` on minute or tick resolution saturates the hourly quota in seconds and burns QCC. Notifications belong on events:

- `on_order_event` (filled / canceled / rejected)
- `on_brokerage_message` (broker errors, disconnects)
- `on_warmup_finished`
- Scheduled events (daily summary, threshold alerts)
- Signal/state transitions — only on the *change*, not on every bar the condition is true.

## Per-channel gotchas

**Email.** Without `headers={'filename': '...'}` the attachment is named `attachment.txt`. Body cap is 10 KB — large diagnostic dumps belong in the attachment or in the Object Store, not inlined in `message`.

**SMS.** `phone_number` must be E.164 (`+14155551234`, not `(415) 555-1234`). It's the only channel without a free quota, so reserve it for genuinely urgent alerts (broker disconnects, halt-trading) and use email/Telegram/webhook for routine notifications.

**Telegram.** `id` is the **group ID** as it appears in the Telegram web URL — a negative integer like `-503016366`. The `token` argument is optional **only** if `@quantconnect_notifications_bot` has been added to the group; otherwise create a bot via `@BotFather` and pass its token. Emojis must be UTF-32 escape sequences (`'\U0001f680'`); literal emoji characters fail the Telegram API encoding.

**Webhook.** HTTP POST with a 300 s response timeout — endpoints that block on synchronous expensive work silently time out, and there is no built-in retry. Discord webhooks expect a JSON body keyed on `content`, not a plain string — wrap with `json.dumps({'content': ...})`. A plain string to a Discord URL is rejected with no visible error in the algorithm.

**FTP vs SFTP.** `notify.ftp` is plaintext. `notify.sftp` is SSH FTP and accepts either `password=` **or** `private_key=` (with optional `private_key_passphrase=`), not both.

## Don't send subscribed dataset content

The notification system **can't be used for data distribution** — that's a terms-of-use violation, not just a quota concern. Send derived information (signal value, portfolio value, fill summary), not raw bars/quotes/trades from QuantConnect-subscribed datasets. The logging skill carries the same rule.

## Shipping trading signals: use signal exports, not webhooks

If the goal is to feed an algorithm's portfolio targets to a fund, allocator, or trading platform — Collective2, Numerai, vBase, or your own endpoint — use signal exports, not `notify.web`. The signal-export manager debounces fills into one batched call (default 5 s window), passes a standardized `PortfolioTarget` list, and has bundled providers for the common destinations. See the custom-signal-export skill. Reach for `notify.*` only for genuine notifications (fill alerts, broker errors, daily summaries) where a human, not a trading system, is the recipient.

## Receiving messages

Notify is one-way out. To *receive* external instructions (manual liquidate, parameter change), use the live-commands skill.

## Quick checklist when adding a notification

1. Is the call gated with `self.live_mode`?
2. If the channel is used more than once, is the call routed through a `_notify_*` helper with the recipient/credentials lifted to module-level constants?
3. Is the call site an event handler or per-bar `on_data`? If per-bar, can it be gated to a real state change?
4. For SMS: is the alert urgent enough to justify per-message QCC cost? Phone number in E.164 format and body under 1,600 chars?
5. For email: is the attachment filename set via `headers={'filename': ...}` and the body under 10 KB?
6. For Telegram: group ID a negative integer, bot present in group (or `token` passed), emojis as UTF-32 escapes?
7. For webhook: receiver responds within 300 s? Discord targets wrapped in a `content`-keyed JSON object?
8. For FTP vs SFTP: auth method matches the server (password vs SSH key, not both)?
9. Does the message body contain raw subscribed-dataset content? If yes, swap for a derived value.
10. Is the payload actually a trading signal / portfolio target headed to a fund or platform? If yes, use signal exports (custom-signal-export skill), not `notify.web`.
11. Does the algorithm need to *receive* messages? That's the live-commands skill.
