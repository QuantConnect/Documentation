---
name: data-export
description: >-
  Use when someone asks whether or how they can get data out of QuantConnect
  Cloud, and whenever you write an algorithm or notebook that saves to the
  Object Store, logs, plots or sends notifications. Triggers: "download my
  Object Store files", `lean cloud object-store get`, the Get Object Store
  File API, "Organization does not have derivative export enabled", "export
  to CSV/Excel", "download my trained model", "get the data into my local
  Jupyter or Colab", "save the history to the Object Store",
  `self.object_store.save` / `ObjectStore.Save`, "which plan lets me
  download", "keep my data after a downgrade", prospects asking what they may
  export before they sign up. Skip for what buying data to run locally
  costs (use the pricing skill) and for Object Store quotas.
language-neutral: true
---

# Data export

One rule explains every answer: the data QuantConnect licenses from its
vendors stays in the cloud. That is what lets QuantConnect offer it at a
fraction of its direct cost. Say what the person can do, then the in-cloud way
to reach their goal.

## What can leave the cloud

| Route | Who | What leaves |
| --- | --- | --- |
| Object Store download: the Download button ([docs](https://www.quantconnect.com/docs/v2/cloud-platform/object-store#04-Download-Files)), `lean cloud object-store get` ([docs](https://www.quantconnect.com/docs/v2/lean-cli/object-store#04-Download-Files)), the [Get Object Store File](https://www.quantconnect.com/docs/v2/cloud-platform/api-reference/object-store-management/get-object-store-file) API | [Institution](https://www.quantconnect.com/docs/v2/cloud-platform/organizations/tier-features#06-Institution-Tier) tier organizations that signed the derived data agreement, and no one else | Derived data only, such as trained models and signal files. Raw price and alternative data never |
| [Download Results](https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/results#17-Download-Results) on a backtest | Everyone | Runtime statistics, charts, the Overview tab and the Orders tab, as JSON |
| The [Read Backtest](https://www.quantconnect.com/docs/v2/cloud-platform/api-reference/backtest-management/read-backtest) and [Read Live Algorithm](https://www.quantconnect.com/docs/v2/cloud-platform/api-reference/live-management/read-live-algorithm) API | Everyone | The same results, for backtests and live algorithms |
| Logs | Everyone, within the [log quota](https://www.quantconnect.com/docs/v2/cloud-platform/organizations/resources#09-Log-Quotas) | The algorithm's own messages. The [Terms](https://www.quantconnect.com/terms) forbid using logs to export dataset information |
| Project files | Everyone | Source code, with [`lean cloud pull`](https://www.quantconnect.com/docs/v2/lean-cli/projects/cloud-synchronization#02-Pulling-Cloud-Projects) |
| [Live notifications](https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/notifications) and [signal exports](https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/signal-exports) | Live algorithms, within the notification quota, to any endpoint | Alerts and trading signals, such as orders, insights and holdings. [Notifications](https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/notifications#08-Terms-of-Use) can't be used for data distribution |
| Download licence from the Dataset Market ([licences](https://www.quantconnect.com/docs/v2/cloud-platform/datasets/licensing), [LEAN CLI](https://www.quantconnect.com/docs/v2/lean-cli/datasets/quantconnect)) | Paid organizations that buy it | The dataset's files, for the organization's internal LEAN use only, never redistributed or converted into another format. Chart images may be shared if the data can't be reconstructed from them. For anything beyond these terms, such as which software may read the files, point to the CLI data agreement (`quantconnect.com/terms/data/`, needs a login) and support |

Nothing else. There is no API that streams cloud history to a local notebook,
Colab or other software.

## Answer

- **The Object Store gate is per organization, not per file.** It covers every
  file, including the person's own uploads and what their algorithms wrote.
  The store can't tell those apart from data derived from licensed datasets,
  so "it's my own code", "there's no market data in it" or "just one CSV"
  changes nothing. There is no per-file or per-organization exception, and no
  need to ask what is in the files.
- **Only the Institution tier can download**, after signing the derived data
  agreement, and then only derived data. Free, Quant Researcher, Team and
  Trading Firm can't, and adding Object Store capacity doesn't change that. A
  fund or company interested in the Institution tier
  [contacts QuantConnect](https://www.quantconnect.com/appointments).
- **`Organization does not have derivative export enabled`** means the
  organization isn't permissioned. It is not a bug or a setting to toggle.
- **Give the in-cloud route for their goal:**
  - Analyze a file: read it in the
    [Research Environment](https://www.quantconnect.com/docs/v2/research-environment/object-store#05-Read-Data)
    or a [backtest](https://www.quantconnect.com/docs/v2/writing-algorithms/object-store#05-Read-Data)
    ([walkthrough](https://www.quantconnect.com/docs/v2/writing-algorithms/object-store#13-Example-for-Logging)).
  - Use a trained model: train in research, save it to the Object Store, load
    it in backtests and live algorithms.
  - Keep backtest output: plot the values and use Download Results; log small
    values within the log quota.
  - Back up source code: keep it in project files and pull it with
    `lean cloud pull`, not in the Object Store.
  - Act on live signals elsewhere: live notifications or signal exports.
  - Work with the data on their own machine: buy a Download licence and run
    LEAN locally (the pricing skill has the cost), or bring their own data.
- **Someone evaluating the platform** gets the rule up front: data used on
  QuantConnect stays in the cloud, derived data included, and there is no
  exception. Buying data to run locally is a different question, and the
  answer is yes: the dataset's Downloading Data page
  (`https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/<vendor>/<dataset>`)
  has the licence terms.
- **Downgrading to Free deletes the Object Store.** Free organizations have no
  Object Store, and an organization that isn't permissioned can't export first,
  so the deletion is final. To lower the bill instead, downgrade to Quant
  Researcher and remove every other subscription, or pause.
  [Change Organization Tiers](https://www.quantconnect.com/docs/v2/cloud-platform/organizations/billing#07-Change-Organization-Tiers)
  lists everything a downgrade deletes.
- **Link** the page with more detail than the answer; a complete answer needs
  none.

## Writing code

Algorithms and notebooks you write follow the same rule:

- Save to the Object Store only what the code derives: trained models,
  signals, parameters, statistics and the algorithm's own orders and fills.
- Never save price, quote, tick, fundamental or alternative data to the Object
  Store, whether as history DataFrames, bar or tick dumps, or files built from
  them. Request history again when the code needs it.
- Keep that data out of logs, plots, runtime statistics and notifications too.
- When asked to save or export dataset data, say it can't leave the cloud and
  offer the in-cloud route for the goal instead.
