# Cloud Platform FAQ

This file answers common questions about QuantConnect Cloud, and each answer links to the full documentation page.

## Welcome

### Is QuantConnect a brokerage?

QuantConnect is an open-source algorithmic trading platform that provides the research, backtesting, and live trading infrastructure. When you trade live, QuantConnect routes your orders directly to the brokerage account you connect, and the brokerage executes them. QuantConnect has never taken payment for order flow.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/security-and-ip#07-Order-Flow>

### Which programming languages does QuantConnect support?

QuantConnect supports Python and C#. Both languages run on LEAN, the open-source, cross-platform trading engine that powers the platform.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/welcome>

### What data does QuantConnect provide?

The QuantConnect data library includes Equities, Options, Futures, CFDs, Forex, Crypto, Indices, and alternative data. The data comes in tick to daily resolutions, and the trade data spans decades. To see the start date, end date, and resolutions of each dataset, browse the Dataset Market.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/welcome#04-Data-Library>

### How does QuantConnect make money?

QuantConnect sells cloud infrastructure as a service, similar to other cloud compute vendors. Companies that want QuantConnect inside their own firewall can pay a setup and maintenance fee for an on-premises installation.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/welcome#05-Business-Model>

## Getting Started

### How do I run my first backtest and paper trade it?

Log in to the Algorithm Lab and, on the Projects page, click **Create New Algorithm**. In the IDE, click the **Build** icon and then the **Backtest** icon to see the backtest results. To paper trade, click the **Deploy Live** icon, select **Paper Trading** in the **Brokerage** field, and click **Deploy**. To trade with a real brokerage, select it instead and enter the authentication details its documentation lists.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/getting-started>

## Security and IP

### Who owns the code I write on QuantConnect?

You own all your intellectual property and your code. Your code is private by default. It only becomes accessible to others when you share it in the forum or add another member as a collaborator. Code you share in the forum becomes public domain.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/security-and-ip#02-Intellectual-Property>

### Can I download QuantConnect data to my computer?

To use QuantConnect data on your own computer, buy a download license for the dataset. Section 2.6 of the Terms of Service prohibits exporting cloud data by any means, even small amounts or for personal, academic, or testing purposes. With a download license, you download the dataset with the LEAN CLI and use it with LEAN on your own computer.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/security-and-ip#08-Data-Use>

### What counts as exporting data from QuantConnect?

Exporting is any route that moves dataset values, or values that can reconstruct them, out of the cloud. Examples include writing prices to logs, charts, runtime statistics, order tags, or notifications. Downloading Object Store files, sending data through network requests, and copying values from screenshots also count. Encoding, compressing, or splitting data across backtests or accounts to work around these limits breaches the Terms of Service.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/security-and-ip#08-Data-Use>

### What can I take out of QuantConnect Cloud?

You can take out the following items:

- Your source code, with `lean cloud pull`.
- The summary results of backtests and live algorithms, such as statistics, equity curves, and order history.
- Trading signals sent with notifications or signal exports.
- Derived files, such as trained models, if your organization is on the Institution tier and signed the derived data agreement.
- Datasets you buy with a download license.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/security-and-ip#08-Data-Use>

### How can I check QuantConnect data without exporting it?

Validate the data inside the cloud. Assert that values match or count the missing days, and print the result of the check instead of the values. To compare with your own reference data, upload your files to the Object Store and compare them in a research notebook. To see a gap or spike, plot the series, or inspect values with the debugger.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/security-and-ip#08-Data-Use>

### Am I responsible for what an AI agent does with my QuantConnect account?

Yes. Every action an AI agent or automated tool takes with your account, credentials, or API tokens counts as your own action. The rule applies even if the agent misreads its instructions or doesn't know the Terms of Service. Review the code it writes and the API and MCP calls it makes.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/security-and-ip#08-Data-Use>

### Can QuantConnect staff see my algorithms?

QuantConnect limits database access to a few senior staff members and asks for your permission before it accesses your algorithm for debugging. When you open a support ticket and attach a project, you grant the support team member access to that project. Only a restricted subset of the support team, with at least two years at QuantConnect and a background check, can access private tickets. You can remove the support agent from the project at any time.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/security-and-ip#02-Intellectual-Property>

### How does QuantConnect protect my code on its servers?

QuantConnect hosts its servers in an Equinix facility with 24/7 security, and only vetted staff can access them physically. The database and backups are encrypted, traffic is encrypted, and most servers sit on private networks. Before your code deploys, QuantConnect compiles and obfuscates it, so a compromised server makes your strategy hard to read.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/security-and-ip#03-Security>

### How can I make my code unreadable on QuantConnect?

Use Project Encryption, which encrypts your code at rest with AES256 and a key stored in your browser. Unless your organization is on the Institution tier, Project Encryption is the only supported way to make code unreadable. The security review must read all code, so you may not obfuscate it with encoded, packed, or dynamically executed source code.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/security-and-ip#05-Code-Encryption>

### Does QuantConnect sell my personal data?

QuantConnect never sells or publishes your email address, and it complies with GDPR and other privacy laws. It asks for your real identity to comply with its data licenses, but you can use an alias on your public profile.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/security-and-ip#04-Privacy>

### Can I run QuantConnect on-premises for compliance reasons?

Yes. The Local Platform installs QuantConnect technology fully on-premises, with the same interface as the cloud and support for offline "anonymous" project development. Institution tier clients can also run LEAN Enterprise, the proprietary build of LEAN, on-premises.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/security-and-ip#06-On-Premise-Installations>

## Organizations

### Does buying more Object Store storage let me download files?

No. Storage subscriptions only add capacity. Downloading stays an Institution tier feature that requires the derived data agreement.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/object-store#05-Storage-Sizes>

### What are the backtest log limits on each tier?

The Free tier allows 10KB of logs per backtest and 3MB per day. Quant Researcher allows 100KB and 3MB, Team allows 1MB and 10MB, and Trading Firm allows 5MB and 50MB. The Institution tier has no log limit. To check your remaining log space, open **Organization > Resources** in the Algorithm Lab.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/resources#09-Log-Quotas>

### Why didn't my daily log quota reset at midnight?

The daily log quota restores over a rolling 24-hour window, so logs you produced yesterday evening still count this morning. Deleting a backtest or project that produced logs doesn't restore the quota. To stay within the limits, log changes in state instead of every time step, and use the debugger to inspect objects.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/resources#09-Log-Quotas>

### What does the Free tier include?

The Free tier gives you cloud access to the datasets in the Dataset Market at minute to daily resolution for backtests and research. It includes one backtesting node, one research node, the IDE autocomplete and debugger, and the report generator. The Free tier has no live trading node quota.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/tier-features#02-Free-Tier>

### What do I get when I upgrade to the Quant Researcher tier?

The Quant Researcher tier adds API access, the LEAN CLI, second and tick resolution data, and parameter optimization. You can have up to two backtesting nodes and two live trading nodes, and you can buy support seats. Quant Researcher organizations have no limit on the number of projects.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/tier-features#03-Quant-Researcher-Tier>

### Which tier do I need to collaborate with other people?

You need the Team tier or higher. The Free and Quant Researcher tiers only allow one member per organization. Team organizations can have up to 10 members, and Trading Firm and Institution organizations have no member limit.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/members#05-Membership-Quotas>

### What's the difference between the Trading Firm and Institution tiers?

Trading Firm organizations own all member projects, grant member permissions, use custom LEAN builds, and can manage Interactive Brokers Financial Advisor accounts. The Institution tier adds on-premises deployment, Quant Bridge and Bloomberg FIX trading, custom libraries, and unlimited backtest logs. Institution is also the only tier with Object Store downloads, under the derived data agreement.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/tier-features#06-Institution-Tier>

### Why does my backtest wait 20 seconds before it starts?

The free B-MICRO backtesting node in your first organization adds a 20-second delay when it launches a backtest. It also caps usage at 200 backtests per day. Both restrictions go away when you upgrade to a paid tier and add a new backtesting node.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/resources#02-Backtesting-Nodes>

### How many nodes can each organization tier have?

The Free tier can have one backtesting node, one research node, and no live trading nodes. Quant Researcher can have two backtesting, one research, and two live trading nodes. Team can have 10 of each type, and Trading Firm and Institution have no node limits.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/resources#07-Node-Quotas>

### Are nodes shared between the members of my organization?

Yes. Members of an organization share all its nodes, so several quants can share one backtesting, research, and live trading node. When you launch a job, it uses the best-performing available node unless you select a specific one.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/resources#06-Sharing-Resources>

### Which live trading node size does my algorithm need?

Each security subscription needs about 5MB of RAM, so the 512MB L-MICRO node holds on the order of 100 subscriptions. Size the node so your algorithm averages below 80% of its RAM limit. LEAN tolerates brief spikes during universe selection or bulk history requests, but a sustained level above the limit stops the algorithm.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/resources#04-Live-Trading-Nodes>

### Why is my GPU node slower than a CPU node?

Moving data to the GPU takes time, so algorithms that don't train machine learning models can run slower on GPU nodes. GPU nodes perform best on repetitive, highly parallel tasks such as model training. Up to three members share each GPU backtesting or research node, and up to two members share each GPU live trading node.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/resources#02-Backtesting-Nodes>

### How long can my algorithm train a machine learning model?

Algorithms normally must return from `on_data` (`OnData`) within 10 minutes. The `train` (`Train`) method gives you extra time from a reservoir that refills over real-world time. Each node model has its own capacity and refill rate. In backtests, `train` blocks the algorithm. In live trading, it runs asynchronously, so check that the model is ready before you use it.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/resources#08-Training-Quotas>

### How am I charged when I add or remove a node?

Adding a node renews your entire subscription period, and QuantConnect charges the price difference between your current and new subscription. Removing a node also renews the period, and your organization receives a pro-rated credit for future invoices. You need billing permissions to add or remove nodes.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/resources#15-Add-Nodes>

### How much storage do my project workspaces have?

Each project workspace holds a project's files, backtest results, optimization results, and live results. Each user has a total workspace quota that grows with the tier, from 1GB on Free to 100GB on Institution. To stay within it, log sparingly and delete old projects and backtest results.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/resources#12-Project-Workspace-Quotas>

### Who can use the Object Store?

Paid organizations can use the Object Store, a key-value store shared across all the projects in the organization. On the Free tier, algorithms that try to save data log "The current user does not have permission to write to the organization Object Store". Paid organizations get 50MB of free storage and can subscribe to more.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/object-store#01-Introduction>

### Do I need a support seat to open a support ticket?

Yes. To submit a support ticket, you need a Bronze, Silver, or Gold support seat in your organization. Any member can report a bug by sharing a simple backtest that reproduces it through email, Discord, or the forum. To report a historical data issue, use the Data Issues page.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/support#02-Features>

### How many support tickets do I get and how fast does support respond?

Gold seats get 16 tickets with a 24-hour response time, and Silver seats get 8 with 48 hours. Bronze seats get 4 with 72 hours or best effort. The quotas restore over a rolling one-month window, so they don't reset on the first day of the month or on your billing date.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/support#10-Ticket-Quotas>

### Can support help me fix a live trading algorithm?

Yes, if you have a paid support seat. Open a support ticket and attach the project and the live deployment, and the support team helps you find the issue, fix it, and redeploy. The support team can't assist with live trading issues in the forum or Discord.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/support#02-Features>

### How do I add a member to my organization?

You need a vacant team seat and team add permissions. On the **Team Management** page, click **Add Member** and enter the email address the person uses to log in, or generate an invite link. The new member must switch to your organization to access its resources.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/members#06-Add-Members>

### Can other members of my organization see my projects?

Members can only access the projects that the owner shares with them as collaborators. Organization membership gives access to the organization's resources, such as nodes and datasets.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/members#01-Introduction>

### What happens to a member's projects when I remove them from the organization?

On the Team tier, the projects the member created stay with the member. On the Trading Firm and Institution tiers, the projects transfer to the organization manager. Removing a member can't be undone, so stop the nodes they use first.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/members#08-Remove-Members>

### Which member permissions can I set?

Managers of Trading Firm and Institution organizations can grant billing, stop node, team, and storage permissions. Billing permissions let a member change subscriptions and billing details. Stop node permissions apply to backtesting, research, and live trading nodes. Team permissions control adding, removing, and editing members, and storage permissions control writing, deleting, and buying Object Store space.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/members#09-Permissions>

### Who owns the projects in an organization?

On the Free and Quant Researcher tiers, you own your projects because you are the only member. On the Team tier, the member who creates a project owns it. On the Trading Firm and Institution tiers, the organization owns all member projects, which keeps consultant code with the firm.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/administration#02-Intellectual-Property>

### Why don't I see my projects after I switch organizations?

The project list shows the projects you can access in the organization you're connected as. To change it, click **Connected as** in the top navigation bar and select the organization. To open a specific organization by default, set it as your preferred organization.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/structure#01-Introduction>

### What happens if I downgrade to the Free tier?

Downgrading to the Free tier permanently deletes data. It deletes your backtest results, logs, Object Store data, and projects that exceed the Free tier limits. It also stops live algorithms and deletes their logs. To lower your bill and keep your data, downgrade to the Quant Researcher tier and remove every subscription except the seat.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/billing#07-Change-Organization-Tiers>

### How do I save my work before I downgrade?

To keep your projects, install the LEAN CLI and run `lean cloud pull`. To keep your results, call the Read Backtest and Read Live Algorithm API endpoints and store the responses. Object Store data can't be exported unless your organization has Institution tier download permission.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/billing#07-Change-Organization-Tiers>

### Can I pause my subscription?

Yes. On the Downgrade page, click **Pause for 1 month** or **Pause for 2 months**. Pausing suspends access to your account but keeps your cloud configuration and project files. To resume early, click **Resume** on the Billing page, and the credit you receive is pro-rated for the pause.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/billing#08-Pause-and-Resume-Subscriptions>

### Does QuantConnect store my credit card details?

No. QuantConnect passes your billing information to the Stripe billing system. Subscriptions renew automatically each month because cancelling a subscription stops your live trading nodes.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/billing#01-Introduction>

### What is QuantConnect Credit (QCC) used for?

QCC enables micropayments on QuantConnect, at a rate of 1 QCC = $0.01 USD. You spend it on parameter optimizations, dataset downloads, forum rewards, and live notification overages. Your organization owns the QCC balance, so every member can spend it.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/credit#01-Introduction>

## Learning Center

### What is the Learning Center?

The Learning Center is a coding environment where you learn quantitative trading with LEAN through courses from QuantConnect and community educators. You manage portfolios, use indicators, trade universes, and automate trades as you go. After you finish a course, you can keep the code to research further or trade live.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/learning-center/training#01-Introduction>

### Do I need to complete the Bootcamp before I can post in the forum?

Yes. You must complete 30% of the Bootcamp lessons before you can post in the community forum.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/learning-center/training#01-Introduction>

### How do I check my answer to a Learning Center task?

Update the `main.py` file and backtest it in the Learning Center environment. The results show whether you passed, and if you did, you can continue to the next task. If you need help, show the hint or open the solution file at the bottom of the instruction panel.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/learning-center/course-structure#04-Results>

### What do I do if a Learning Center task fails even though my code looks right?

Compare your code to the solution file. The error can come from your submission or from the Learning Center backend. If the backend causes it, email the task URL, an explanation of the issue, and a code snippet that reproduces the error.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/learning-center/course-structure#06-Errors>

### Can I use the Learning Center to train my team?

Yes. The Learning Center tracks your team members' progress on the courses that you buy or create. Paid courses cost the listed price times the number of members in your organization, and members get lifetime access. Institutional clients can upload private courses to **Organization > Resources** for internal onboarding.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/learning-center/training#03-Paid-Courses>

### How do I become a Learning Center educator?

Anyone with skills in quant finance, web development, C# and Python, or Jupyter can register by contacting QuantConnect. Educators earn a $5,000 commission per accepted course and a 70% revenue share on their courses.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/learning-center/educators>

## Projects

### How do I recover a project I deleted?

Deleted projects move to the **Recycle Bin** directory on the My Projects page, where they stay for 30 days. To recover one, open it from the **Recycle Bin** and rename it to remove the `Recycle Bin/` prefix from its path. After 30 days, QuantConnect deletes the project permanently.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/getting-started#11-Delete-Projects>

### Why do I get "No coding session available" when I open a project?

Each open project uses a coding session, and your tier sets how many you can have at once. Free users get one session across all their free organizations, while paid tiers allow 2 to 16 per account in each organization. Logging out doesn't stop a session, so stop unused ones from the Projects page.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/code-sessions#03-Code-Session-Quotas>

### How many files can a project have and how large can they be?

The limits depend on your organization tier. Free projects can have 25 files of up to 32KB, and Institution projects can have 250 files of up to 256KB. If a project reaches the quota, split the code into more files, move stable code into a shared library, or upgrade your tier.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/files#10-Quotas>

### Which file types can a project contain?

Cloud projects support `.py`, `.cs`, `.ipynb`, `.html`, and `.css` files. To use data files such as CSV files in your algorithm, upload them to the Object Store instead.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/files#02-Supported-File-Types>

### What's the difference between cloning and migrating a project?

Cloning copies a project into the same organization with its files, but without its backtest results and live deployment history. Migrating moves a project to another organization, and only organization administrators can do it. A migration copies the project files but doesn't keep the content stored in the Object Store.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/getting-started#06-Migrate-Projects>

### Why are my project files read-only with a lock icon?

Only one collaborator can edit a project at a time, including AI assistants that edit files for you. When someone else has edit control, the file tabs show a lock icon. To edit, click **Request Edit Control** in the **Collaborate** section of the Project panel.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/collaboration#06-Request-Edit-Control>

### How do I share a project with a teammate?

You must own the project. In the **Collaborate** section of the Project panel, click **Add Collaborator** and select a member. To share with everyone, select the entire organization, which uses one collaborator slot. Select **Live Control** if the member can manage live deployments.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/collaboration#03-Add-Team-Members>

### How many collaborators can I add to a project?

Team and Trading Firm organizations can add 10 collaborators per project, and Institution organizations have no limit. Free and Quant Researcher organizations don't support collaboration. This quota is separate from the number of members in your organization.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/collaboration#04-Collaborator-Quotas>

### Why can't my collaborator open the library in my shared project?

Adding a collaborator to a project doesn't give them access to its shared libraries. To grant access, add them as a collaborator to the library project too.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/collaboration#03-Add-Team-Members>

### Can I encrypt a project that has collaborators?

No. Project Encryption is available for projects without collaborators. An encrypted project can use unencrypted libraries and libraries encrypted with the same key.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/encryption#07-Collaboration-Support>

### How does project encryption work?

You provide your own encryption key, a `.txt` file with at least 32 characters, and your browser keeps it in local storage. When you save, your browser encrypts the project files and only pushes the encrypted files to QuantConnect Cloud. QuantConnect records an MD5 hash of the key to know which key encrypted each project.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/encryption>

### Why can't I open my encrypted project on a new computer?

Your encryption key lives in the local storage of the browser where you added it. On a new computer, the **Encryption Keys** section of the organization homepage shows the key as missing. Click **Use Key** and upload the key file to decrypt your projects in that browser.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/encryption#02-View-All-Keys>

### Should I use Python or C#?

Python is less verbose, has more third-party libraries, and is the native language of the research notebooks. C# runs faster and makes it easier to contribute features to LEAN. Your account's language setting decides the file types of new projects and how autocomplete works.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/ide#02-Supported-Languages>

### How do I change my default programming language?

In the top navigation bar, click your username, then **My Account**. In the **Account Settings** section, click **C#** or **Python**. New projects then start with files in that language.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/ide#03-Change-Languages>

### Why doesn't autocomplete show my own classes?

IntelliSense covers the default class members in LEAN, but it doesn't support class names or user-defined objects. To trigger autocomplete, type the first few characters of a member and press Ctrl+Space.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/ide#04-Autocomplete-and-Intellisense>

### Can I open the same project in two browser tabs?

Use the split editor instead of a second browser tab. Two tabs create two code sessions that update the same project, and the sessions fall out of sync.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/ide#10-Split-the-Editor>

### What do I do if the IDE won't load?

Use a supported browser such as Chrome, Edge, Firefox, or Safari, and test a second one. Disable your VPN and browser add-ons, and check that your antivirus allows the IDE's Service Workers. If panels show "Server IP address could not be found", switch to Google Public DNS. Clear your browser cache, and if the problem persists, send the browser console logs to support.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/ide#15-Troubleshooting>

### Why does my log file skip some of my log messages?

LEAN only adds the first instance of identical log content to the log file. To log the same message more than once, add a timestamp to it. Logging dataset values isn't permitted, so log events and state changes instead.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/debugging#04-Logging-Statements>

### Why are my debug messages cut off or delayed?

Debug messages can be up to 200 characters long. If you send several debug statements within one second, the platform rate-limits them to protect your browser, and error statements follow the same rule. Use `log` (`Log`) for routine records and keep `debug` (`Debug`) for the few messages that must stand out.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/debugging#04-Logging-Statements>

### How do I record why each trade happened without using my log quota?

Pass a tag to the order method. The tag travels with the order and appears in the results, so it doesn't consume your log quota.

```python
self.stop_market_order(symbol, -quantity, stop_price, tag="stop loss")
```

```csharp
StopMarketOrder(symbol, -quantity, stopPrice, tag: "stop loss");
```

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/debugging#05-Order-Tagging>

### Can I change parameter values while my algorithm runs?

No. LEAN sends the parameter values to your algorithm when you deploy it. Read them with `get_parameter` (`GetParameter`), and set default values in the Project panel. Parameters also let you run parameter optimizations.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/structure#06-Parameters>

### How do I reuse code across several projects?

Create a shared library, which is a project that you add to other projects. In the Project panel, click **Add Library**, choose the library, and import it in your code. Every project that uses the library picks up your edits, so add code to a library when it's stable.

```python
from Calculators.TaxesCalculator import TaxesCalculator
```

```csharp
using Calculators;
```

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/shared-libraries#03-Add-Libraries>

### How do I use a Python library that isn't in the default environment?

QuantConnect bundles compatible libraries into separate environments. To switch, open the Project panel, click the **Python Foundation** field, and select an environment. The Supported Libraries page lists the libraries in each environment.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/package-environments#02-Set-Environment>

### How do I request a new library on QuantConnect Cloud?

Contact QuantConnect to request it. The team reviews each library for security before it runs on the servers, and the process takes 2 to 4 weeks. The library-request issues on the LEAN GitHub repository show the libraries under review.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/package-environments#99-Request-New-Libraries>

### Which version of LEAN runs my algorithm?

By default, the latest master branch of LEAN runs your backtests, research notebooks, and live algorithms, because it includes all bug fixes. Trading Firm and Institution organizations can select an older or custom branch per project in the **LEAN Engine** field. New projects use the master branch.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/lean-engine-versions>

### What can I name a project?

Project names can only contain letters, numbers, spaces, hyphens, and underscores, and they can't start with a space. Reserved names such as CON, PRN, AUX, NUL, COM1, and LPT1 aren't allowed. To put a project in a directory, name it `directoryName / projectName`.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/getting-started#07-Rename-Projects>

## Research

### Can I share a research notebook with someone outside my organization?

Save the notebook, run a backtest, and make the backtest public to generate a share link. The other person can clone the project from the link and open the notebook files in their own Research Environment. To share with your team instead, add them to the project.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/research/deployment#03-Sharing>

### Can I train a model in research and use it in live trading?

Yes. Train the model in the Research Environment and save it in the Object Store. Then load it from the Object Store in your backtest or live algorithm. The Object Store is available to paid organizations.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/research/getting-started#01-Introduction>

### Why do profitable results in research fail in backtests or live trading?

Research notebooks can introduce look-ahead bias because all the data is available at the same time. For example, you can trade at a closing price that you also used to make the decision. Backtests avoid this because LEAN feeds data in time slices and fills orders on a later slice. Also test models on data you didn't train them on.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/research/deployment#05-Look-Ahead-Bias>

### Which research node do I get for free?

Your first organization includes one free R1-4 research node. When you subscribe to a paid research node in that organization, the paid node replaces it. GPU research nodes are shared among up to three members.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/research/deployment#02-Resources>

## Backtesting

### Why does my backtest stop before today's date?

Your organization has an out-of-sample period, which makes every backtest end a set number of months before the current date. The backtest runs up to the start of that holdout period. Organization managers can change the period or select **No Holdout Period** on the organization homepage.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/getting-started#05-Out-of-Sample-Period>

### How many backtests can I run at the same time?

Each backtest runs on one backtesting node, so the number of nodes in your organization sets how many backtests run concurrently. The Free tier allows one node, Quant Researcher two, Team 10, and Trading Firm and Institution have no limit. To test many parameter combinations at once, run a parameter optimization.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/deployment#03-Concurrent-Backtesting>

### Can I close my browser while a cloud backtest runs?

Yes. The backtest runs on QuantConnect's servers, so you can refresh or close the IDE without interrupting it. To reopen the results, open the project's list of backtests.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/results#02-View-Backtest-Results>

### What is the longest a backtest can run?

A backtest can run for up to 12 hours. The runtime depends on the amount of data, your algorithm's computations, and the backtesting node type.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/deployment#10-Runtime-Quota>

### How many orders can a backtest place?

A Free tier backtest can place up to 10,000 orders, and a Quant Researcher tier backtest can place up to 10 million. Team, Trading Firm, and Institution organizations have no order limit.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/deployment#05-Orders>

### How do I stop a running backtest?

Open the **Resources** panel in the IDE and click the stop icon next to the backtesting node. You can stop nodes that you use, but you need stop node permissions to stop nodes that other members use.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/deployment#09-Stop-Backtests>

### Why is my backtest results page empty?

If a backtest produces more than 700MB of data, LEAN can't upload the results, so the results page appears empty.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/results#20-Errors>

### What can I download from my backtest results?

On the **Overview** tab, click **Download Results** to get the runtime statistics, built-in charts, Overview data, and Orders data as a JSON file. The **Orders**, **Trades**, **Insights**, and **Logs** tabs each have their own download button.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/results#17-Download-Results>

### Why do the timestamps in my results use different time zones?

The Orders, Trades, and Insights tables on the results page use Eastern Time. The CSV and JSON files you download use Coordinated Universal Time. The log file uses your algorithm's time zone.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/results#11-Orders>

### Why doesn't the orders CSV contain everything in the Orders tab?

The orders CSV contains what the Orders table shows when its rows are collapsed. To get the full content, including every order event, use **Download Results** on the **Overview** tab. To analyze orders in a notebook, call `read_backtest_orders` (`ReadBacktestOrders`).

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/results#11-Orders>

### How do I share a backtest?

In the **Share Results** section of the results page, click **Make Public**, and then copy the backtest URL or the embed code. Anyone with the link can see the results and the project files. To hide them again, click **Make Private**.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/results#16-Share-Results>

### How long does QuantConnect keep my backtest results?

QuantConnect archives your backtest results if you delete them or if you're inactive for 12 months. Unless you make a backtest public, only you can view its results.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/results#02-View-Backtest-Results>

### How do I get back the code that produced an old backtest?

Open the backtest results page and click the **Code** tab to see the project files the backtest used. Click **Clone Algorithm** to create a new project with those files. You can also hover over a backtest in the project's backtest list and click the clone icon.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/results#15-Project-Files>

### Why don't the fill prices on the asset plot match the price line?

The asset plot shows fills at their actual fill prices. Fill prices differ from the plotted price because of slippage and because quote data fills at the bid or ask. Some fill models also fill at the bar's high or low. Asset plots are available on all paid organization tiers.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/results#06-Asset-Plots>

### How many charts and data points can I plot?

The limits depend on your tier, from 10 series with 4,000 points each on Free to 100 series with 96,000 points each on Institution. If you exceed the series quota, your algorithm stops. If you exceed the points quota, LEAN skips the extra data. For larger plots, save the data in the Object Store and plot it in the Research Environment.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/results#07-Custom-Charts>

### How do I see CPU, RAM, and time usage of my backtest?

Enable the Performance chart, which is off by default, by setting a sampling period. The chart shows CPU, RAM, active securities, data points, and the time LEAN spends in each part of your algorithm.

```python
self.settings.performance_sample_period = timedelta(7)
```

```csharp
Settings.PerformanceSamplePeriod = TimeSpan.FromDays(7);
```

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/results#05-Performance-Chart>

### How do I add my own statistics to the results banner?

Add custom runtime statistics in your algorithm, and they appear in the banner and in the downloaded results. LEAN keeps at most 50 runtime statistics and truncates names and values to 200 characters. It ignores encoded values, such as base64 or hexadecimal strings.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/results#03-Runtime-Statistics>

### How do I generate a PDF report of my backtest?

Open the backtest results page, click the **Report** tab, and click **Download Report**. If the project has no description, enter one first because the report displays it. The report can take a minute to generate.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/results#10-Reports>

### Can I customize the backtest report?

Yes. Override the report HTML and CSS files and use the report keys from the LEAN repository, such as `{{$KPI-SHARPE}}`. Then generate the report with the LEAN CLI, for example `lean report --html <pathToCustomHTMLFile>`.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/report#10-Customize-Reports>

### Why does the portfolio margin chart show an "Others" area?

The portfolio margin chart only draws the top 5 to 10 holdings at each point in time and groups the smaller holdings into "Others". In backtests, the chart samples once a day, so it doesn't show intraday margin use. Each series name is the asset's last known ticker.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/results/portfolio-margin-plots>

### How do I debug a backtest step by step?

Add at least one breakpoint by clicking to the left of a line number, and then click the **Debug** icon. When execution pauses, the **Run and Debug** panel shows local variables and lets you add watch expressions. You can set breakpoint conditions and hit counts by right-clicking a breakpoint.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/debugging>

### Does QuantConnect limit how many backtests I run on a project?

QuantConnect doesn't restrict the number of backtests per project, apart from the 200-per-day cap on the free B-MICRO node. The Research Guide counts your backtests, parameters, and research time as a guide to overfitting risk. Over 70 backtests, over 20 parameters, or over 16 hours on one idea suggest the strategy is probably overfit.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/backtesting/research-guide#03-Research-Panel>

## Datasets

### How do I report a data issue?

Open the **Data Issues** page in the Algorithm Lab and check that nobody has already reported the issue. Then click **Report Issue**, fill out the form, and attach a small backtest or notebook that reproduces the issue. AI agents and scripts can use the Create Data Issue API endpoint.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/data-issues#04-Report-New-Issues>

### What should I check before I report missing or wrong data?

Some data that looks wrong is correct. Illiquid assets can go minutes without a trade, and halts, delistings, holidays, and early closes stop the data. Splits and dividends create jumps in raw prices, and ticker changes leave history under the old ticker. A quick news search for the asset on those dates usually explains the gap.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/data-issues#02-Common-Issues>

### How do I report a problem with live, real-time data?

The Data Issues page doesn't track real-time data because it comes from the data provider you select when you deploy. First, confirm that your data provider supports the asset. If the issue persists, open a support ticket and attach the project and the live deployment. History requests in live algorithms use the historical datasets, so report those issues on the Data Issues page.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/data-issues#04-Report-New-Issues>

### How should I show a data issue in my report?

Plot the affected series so the gap or spike is visible, and attach a screenshot that marks the wrong dates and values. Attach the smallest backtest or notebook that reproduces the issue, with the affected asset, resolution, and dates only. Logging prices doesn't help because logs are hard to inspect and use your log quota.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/data-issues#05-Best-Practices>

### Why doesn't QuantConnect data match Yahoo Finance or my charting website?

QuantConnect uses the CTA and UTP tick feeds, which cover the entire US market and exclude over-the-counter trades. Many charting websites use BATS, which covers about 6-7% of market volume, and Yahoo Finance includes OTC trades. Time zones, bar timestamps, and data normalization modes also cause differences.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/misconceptions#02-Cross-Platform-Discrepancies>

### Why do QuantConnect bars look one period late?

LEAN delivers each bar at the end of its period to avoid look-ahead bias. A bar's `time` (`Time`) is its start, and its `end_time` (`EndTime`) is its end. In Python history DataFrames, the time index is the bar's `end_time`.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/misconceptions#04-Misaligned-Timestamps>

### Which time zone is QuantConnect data in?

Each dataset uses the time zone of its market. US Equities use Eastern Time, and Forex uses Coordinated Universal Time. Futures use the time zone of their exchange, and each CFD uses the time zone of its product.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/misconceptions#03-Timezone-Differences>

### Why did my backtest results change when I reran it later?

By default, LEAN adjusts US Equity prices with the entire split and dividend history. When a new split or dividend occurs, the adjusted historical prices change, so a backtest you rerun afterward can give different results. Large universes show bigger differences because many small changes add up.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/misconceptions#06-Adjusted-Prices>

### Why do my historical prices differ from another source in the past?

The two sources probably use different data normalization modes to adjust for splits, dividends, and futures rolls. A sign of this is two price series that agree recently and drift apart further back. Compare data with the same normalization mode before you report an issue.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/misconceptions#05-Data-Normalization>

### Why is the opening price different from other platforms?

QuantConnect sets the first and last bars of the day to the official auction prices from the listing exchange. Exchanges publish these prices 2 to 30 seconds after the open and close. Minute bars usually include the official open, but most second bars don't because LEAN must emit them on time.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/misconceptions#07-Opening-and-Closing-Auctions>

### Why do live bars differ from backtest bars for the same minute?

In live trading, LEAN builds bars from exchange timestamps with microsecond accuracy. A tick can land in a different bar in live trading than in the historical data. As a result, the open, high, low, close, and volume can differ slightly.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/misconceptions#08-Live-and-Backtesting-Differences>

### How soon can I backtest on yesterday's data?

New live data is normally available for backtesting after 24 to 48 hours. To monitor new data more closely, run your algorithm in live paper trading.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/misconceptions#08-Live-and-Backtesting-Differences>

### Do I have to pay for data to backtest in QuantConnect Cloud?

Most price data is free to use in the cloud. Some proprietary datasets need a Cloud license, which adds a monthly cost to your bill and covers every member of your organization. You need an organization above the Free tier to buy cloud access to a dataset.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/licensing>

### What's the difference between a Cloud license and a Download license?

A Cloud license lets you use a dataset for research, backtests, and live trading in QuantConnect Cloud. A Download license lets you store the dataset on your computer for your organization's internal LEAN use. Downloads are priced per file or per day, and you can't redistribute the data or convert it to another format.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/licensing#04-Download>

### How do I cancel a dataset subscription?

Open the organization homepage and click **Edit Plan**. On the **Customize Plan > Data** tab, click **Added** next to the dataset, and then click **Proceed to Checkout**.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/licensing#03-Cloud>

### How do I find a dataset and its start date?

In the top navigation bar, click **Data** to open the Dataset Market. Each listing shows the dataset's coverage, start date, and price, and you can filter by category or delivery option. The listing's **Documentation** and **Examples** tabs show the code to use the dataset.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/navigating-the-market#02-View-All-Listings>

### Can I backtest a market that QuantConnect doesn't list?

If the Dataset Market doesn't include the market or asset you need, import your own data as custom data.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/research/deployment#04-Historical-Data>

### What does the QuantConnect live data provider cost and cover?

The QuantConnect data provider serves its live data for free. It covers US Equities, US Equity Options, Crypto, Crypto Futures, Forex, Futures, US Indices, US Index Options, CFDs, and alternative data. US Equity data comes from the SIP, so it covers 100% of the market and excludes OTC trades.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/quantconnect/us-equities#02-Sourcing>

### How do I get live VIX futures data?

The QuantConnect data provider covers CBOT, CME, COMEX, ICE, and NYMEX futures, but it doesn't include the CFE market. For `Futures.Indices.VIX`, combine the QuantConnect and Interactive Brokers data providers.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/quantconnect/futures#02-Sourcing>

### When does live fundamental universe selection run?

The live data for fundamental universe selection arrives at 6 or 7 AM Eastern Time, so selection runs between 7 and 8 AM. Live Crypto universe data arrives at 4 PM UTC, so selection runs between 4 PM and 4:30 PM UTC. Schedule logic that depends on the new universe after selection runs because the data isn't ready at midnight.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/quantconnect/us-equities#03-Universe-Selection>

### How much history can I request in a live algorithm with the QuantConnect data provider?

History requests and warm-up periods can reach all daily and hourly data and one year of minute data. They can reach two months of second data and one month of tick data. For US Equity Options and US Index Options, the densest historical resolution is minute data.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/quantconnect/us-equities#07-Historical-Data>

### How do I use second-resolution Options data live and still backtest the same algorithm?

The live stream provides second resolution for Options, but minute is the densest historical resolution. Choose the resolution based on `live_mode` (`LiveMode`), and request minute or coarser data in history requests and warm-up.

```python
resolution = Resolution.SECOND if self.live_mode else Resolution.MINUTE
```

```csharp
var resolution = LiveMode ? Resolution.Second : Resolution.Minute;
```

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/quantconnect/us-equity-options#05-Historical-Data>

### Why are some ticks missing from my US Equity bars?

The bar-building process excludes suspicious ticks, odd lots, and trades or quotes with certain condition flags. Once FINRA filtering activates for a security, FINRA trades from dark pools and internal crosses don't set the bar price. Excluded trades still add their volume to the bar.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/quantconnect/us-equities#04-Bar-Building>

### What latency does the QuantConnect live data have?

The QuantConnect data latency is 5 to 40 milliseconds. Round trip order times from brokers are a larger source of latency, at 100 milliseconds to 5 seconds. QuantConnect isn't intended for high-frequency trading.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/quantconnect/us-equities#06-Delivery>

### How do I combine several live data providers?

In the deployment wizard, add the data providers in order of precedence. LEAN only uses a provider for the securities that the providers before it don't supply. We recommend the QuantConnect data provider on its own, or first with a second provider that fills its gaps. The QuantConnect data provider doesn't supply Future Options, so trading them live needs this second provider.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/interactive-brokers#06-Hybrid-Data-Provider>

### Do alternative datasets work with a brokerage data provider?

Third-party data providers support most alternative datasets. Streaming datasets, such as the Tiingo News Feed and Benzinga News Feed, need the QuantConnect data provider as another provider. QuantConnect Cloud provides the US Equity Security Master and other auxiliary datasets to every cloud algorithm.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/interactive-brokers#07-Alternative-Data>

### What are the limits of historical data from Interactive Brokers?

IB history excludes delisted Equities, expired Options, and Futures that expired more than two years ago. Second resolution data goes back six months. For tick and second data, you can have 50 simultaneous requests and 60 requests in any 10-minute period, and trade and quote data count separately.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/interactive-brokers#08-Historical-Data>

### Which Interactive Brokers market data subscriptions do I need?

Subscribe to the IB market data for the securities you trade, such as the US Securities Snapshot and Futures Value Bundle. IB can take 24 hours to process a subscription, and it streams up to 100 assets by default. To use your market data with your paper account, enable sharing under **Settings > Paper Trading Account** in the IB Client Portal.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/interactive-brokers#09-Pricing>

### Can I backtest with Polygon or Databento data in QuantConnect Cloud?

No. QuantConnect Cloud only streams Polygon and Databento data in live trading. To use their data for backtesting, research, or optimization, download it with the LEAN CLI.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/polygon>

### How do I get Bloomberg BPIPE data?

Contact QuantConnect support to request BPIPE access. Your organization needs a dedicated BPIPE ticker plant that QuantConnect runs, plus Bloomberg entitlements for each dataset. BPIPE only streams live data, and it can't combine with the QuantConnect data provider.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/datasets/bloomberg-bpipe>

## Live Trading

### What do I need to deploy a live algorithm on QuantConnect Cloud?

You need a live trading node for each algorithm you deploy and a project that compiles. You also need a supported brokerage account, or you can use the QuantConnect Paper Trading brokerage. To deploy, open the project, click Deploy Live, select the brokerage, region, node, and data provider, and then click Deploy. The deployment process can take up to 5 minutes.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/getting-started>

### Can I run more than one live algorithm on a single live trading node?

No, each live algorithm needs its own live trading node. QuantConnect doesn't support sub-algorithms or sharing one server between several algorithms. The tier of your organization sets how many live trading nodes it can have.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/deployment>

### How do I run several live algorithms against the same brokerage account?

Create a sub-account in your brokerage account for each algorithm, so each algorithm has its own brokerage connection credentials. Each algorithm also needs its own live trading node. Many brokerages, such as Interactive Brokers, only allow one connection per login at a time.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/deployment>

### Why does my live algorithm stop with a memory error when the same code backtests fine?

Backtesting nodes have more RAM because LEAN caches many data objects to inject data about 100,000 times faster than live trading. Live nodes have 512 MB to 4 GB of RAM, which is enough for almost all live algorithms. LEAN checks a 10-minute smoothed average against the node limit and lets a single sample reach twice the limit. A brief spike is fine, but a sustained level above the limit stops the algorithm.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/deployment>

### Which regions can host my live algorithm?

QuantConnect Cloud hosts live algorithms in America (NY7, New York), Canada (TOR, Toronto), and Europe (WAW, Warsaw). NY7 is the default. Select the region in the Region section of the Deploy Live page, and the Node field then lists only your nodes in that region.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/getting-started>

### Why do I need the Europe region to trade on Binance or Bybit?

Binance and Bybit restrict access from the United States. To trade with them or to receive their data, deploy your live algorithm to the Europe (WAW) region.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/binance>

### How can I protect my live algorithms against a data center outage?

Spread your live algorithms across regions, so an incident in one region doesn't affect algorithms in the others. If NY7 is unavailable, you can deploy the strategy to TOR. Provision the nodes ahead of time, because redundancy space is first-come, first-served and new servers can take weeks.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/getting-started>

### What is a dedicated host for live trading?

A dedicated host is a large server that QuantConnect provisions for your organization alone. It provides the equivalent of 80 cores and 160 GB of RAM, which is configured as 20 L4-8 live trading nodes by default. Dedicated hosts are available in the NY7 and TOR regions, and you request one by contacting QuantConnect.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/getting-started>

### Does QuantConnect save my brokerage credentials?

No, QuantConnect doesn't save the brokerage credentials that you enter in the deployment wizard. Brokerages that use an authorization flow, such as Charles Schwab or tastytrade, ask you to log in on the brokerage website instead.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### How do I know my existing brokerage positions loaded correctly when I deploy?

After the algorithm deploys, compare the equity value in the runtime statistics, the cashbook, and the position holdings with your brokerage account. Most brokerages load your cash, positions, and open orders automatically. For brokerages that don't, such as FIX connections, enter your cash and holdings in the Algorithm Cash State and Algorithm Holdings State sections.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/deployment>

### What happens if my brokerage account holds assets that LEAN doesn't support?

LEAN exits on deployment, because unsupported assets prevent it from calculating the portfolio value and margin correctly. Move or close those assets before you deploy, or deploy to a separate account or sub-account. The asset class dataset listings show the supported assets.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/deployment>

### What do automatic restarts do, and why did my algorithm stop restarting?

Automatic restarts try to restart your algorithm after a runtime error or an API disconnection. An algorithm makes up to five restart attempts and stops trying after five failures. To prevent restart loops from coding bugs, an algorithm only restarts automatically if it ran for at least five minutes.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/deployment>

### Does my live algorithm keep its indicators and variables after a restart?

No, a restart starts a new instance that runs `initialize` (`Initialize`) again, so class members, indicators, rolling windows, and scheduled events start empty. The algorithm recovers cash, holdings, and, for some brokerages, open orders from the brokerage account. Warm up indicators with history, and save other state in the Object Store and load it in `initialize`.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/deployment>

### What does a QuantConnect Paper Trading deployment recover when I redeploy?

QuantConnect Paper Trading saves the last known cash and holdings. When you redeploy, it pre-fills them in the Algorithm Cash State and Algorithm Holdings State sections. It doesn't restore open orders. Indicators and other algorithm state start empty, as with every restart.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/deployment>

### How do I change the code or parameters of a running live algorithm?

Stop the algorithm, make your changes, and then redeploy it. You can't edit the code or parameter values while the algorithm runs. To change values without a redeploy, add a Scheduled Event that downloads a remote file, or send the algorithm a command.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/algorithm-control>

### What happens to my positions when I stop a live algorithm?

Your portfolio holdings stay in your brokerage account when you stop a live algorithm. Stop the algorithm when you want to update code, upgrade the node, change wizard settings, or place manual orders through your brokerage. Also stop and redeploy after a reverse merger adds new securities to your portfolio.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/algorithm-control>

### Why did my live algorithm stop after I placed a trade in my brokerage account?

LEAN terminates live algorithms when it detects interference from outside the algorithm, to avoid race conditions between you and the algorithm. To adjust your holdings, stop the algorithm, place your trades at the brokerage, and then redeploy. To trade without stopping, place manual orders from the live results page.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/algorithm-control>

### How do I place a manual trade in a running live algorithm?

On the live results page, open the Holdings tab, click the security, click Create Order or Liquidate, and then submit the order. If the security isn't listed, enable Show All Portfolio or add a security subscription first. You can use any order type your brokerage supports, but you can't cancel manual orders.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/algorithm-control>

### What does the Liquidate button do?

The Liquidate button on the live results page sells all your portfolio holdings and then stops the algorithm. If the market is open for an asset, the algorithm uses market orders. If the market is closed, it places market on open orders.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/algorithm-control>

### Can I remove a security that I added from the live results page?

No, you can't remove security subscriptions from the IDE. The Add Security option in the Holdings tab subscribes your live algorithm to a new security so you can trade it manually. To remove it, stop and redeploy the algorithm.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/algorithm-control>

### How do I send instructions into a running live algorithm?

Use commands. Commands call methods in your live algorithm as it runs, so you can update its state, place orders, or run other logic. Notifications send information out of your algorithm, and commands send information in.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/commands>

### How do I clear the results history of a live project?

Clone the project and deploy the cloned version. When you stop and redeploy a live algorithm, the live results, including logs, stay attached to the project between deployments. The runtime statistics in the banner reset on each redeploy.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/getting-started>

### Where do I find the deployment Id of a live algorithm?

Open the live log file and search for "Launching analysis for". The log lists every deployment Id of the project, such as `L-6bf91128391608d0728ff90b81bfca41`. If you deployed the project several times, use the most recent Id.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/getting-started>

### How can my algorithm tell which brokerage account it's connected to?

Read the `deployment_details` (`DeploymentDetails`) property. The brokerage shares key-value details about the deployment, such as the account it trades, and LEAN fills the dictionary before `initialize` runs. The details never include credentials, and the `/live/read` API endpoint returns them in its `deploymentDetails` field.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/getting-started>

### How do I deploy many live algorithms at once?

Use a notebook in the Research Environment to deploy, stop, or liquidate live algorithms programmatically through the QuantConnect API. This approach suits organizations that manage many deployments.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/getting-started>

### Should I paper trade before trading real money?

Yes, we recommend paper trading a strategy before deploying it with real money. Use your brokerage's demo environment if it has one, or set your brokerage model and deploy with QuantConnect Paper Trading. While paper trading, restart the algorithm with the market open and closed, redeploy it, and deploy a clone to check it recovers.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/deployment>

### What is QuantConnect Paper Trading?

QuantConnect Paper Trading runs your algorithm on real-time data and simulates order fills with fictional capital. You don't need a brokerage account to use it. If you don't call `set_brokerage_model` (`SetBrokerageModel`), it simulates trades with the `DefaultBrokerageModel`.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/quantconnect-paper-trading>

### How do I paper trade with the rules of a brokerage that has no paper environment?

In `initialize`, call `set_brokerage_model` with your brokerage and account type, and then deploy with the QuantConnect Paper Trading brokerage. The brokerage model then defines the supported asset classes, order types, fees, and margin. Brokerages without a paper API, such as Charles Schwab, tastytrade, and Kraken, recommend this approach.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/quantconnect-paper-trading>

### Why do my paper trading fills look better than my brokerage fills?

With the `DefaultBrokerageModel`, QuantConnect Paper Trading fills market orders immediately and completely, uses the bid and ask spread when available, and models no slippage. Real brokerages fill from the order book and add slippage. To get closer results, set the brokerage model and reality models of your brokerage.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/quantconnect-paper-trading>

### Which assets can I paper trade on QuantConnect?

With the `DefaultBrokerageModel`, QuantConnect Paper Trading supports US Equities, Crypto, Forex, CFD, Futures, and Future Options. Paper trading is only available for assets that have a live data provider. If you set a different brokerage model, that model defines the asset classes you can trade.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/quantconnect-paper-trading>

### Which live data provider should I select?

We recommend the QuantConnect data provider, which is free and supplies live data for the asset classes listed on each brokerage page. You can add a brokerage or third-party data provider for assets it doesn't cover or to trade on the brokerage's prices. The order of the providers in the deployment wizard sets their precedence in LEAN.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### Do I need a separate data provider to trade Future Options with Interactive Brokers?

Yes, the QuantConnect data provider doesn't supply Future Options data, so combine it with the IB data provider. You might have to buy a market data subscription from IB. The IB data provider streams through the IB API, so it brings back the weekly logins even if you route orders through FIX.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### Which Interactive Brokers account do I need for QuantConnect?

You need an IBKR Pro account, because the IB API doesn't support IBKR Lite. You also need IBKR Mobile Authentication (IB Key) activated. QuantConnect supports cash and margin individual accounts and, for Trading Firm and Institution organizations, Financial Advisor accounts.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### How do I know if my Interactive Brokers account is a Financial Advisor account?

If your IB account code starts with F, FA, or I, it's a Financial Advisor (FA) account. FA accounts let certified professionals manage several client accounts with one algorithm. QuantConnect supports FA accounts for Trading Firm and Institution organizations.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### Which two-factor authentication methods work with Interactive Brokers on QuantConnect?

Only IB Key Security through IBKR Mobile works. The integration doesn't support SMS, the Online Security Code Card, or third-party authenticator apps such as Google Authenticator or Microsoft Authenticator. When you deploy, tap the notification on your IB Key device and enter your PIN.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### Why do I get an Interactive Brokers notification every Sunday?

IB requires you to re-authenticate the connection between IB and your live algorithm every week. You pick the time in the Weekly Restart UTC field of the deployment wizard, and the notification arrives on your IB Key device each Sunday. Keep the device charged for that time.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### What happens if I miss the weekly Interactive Brokers re-authentication?

QuantConnect emails you with Connect and Stop options. Click Connect to restart authentication and get a new IB Key notification, or click Stop to stop the deployment. If you don't act, the algorithm stops before the market opens for the assets in your portfolio.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### Can I use Trader Workstation while my algorithm trades my Interactive Brokers account?

Yes, but log in to Trader Workstation with a second user on your IB account. By default, IB supports only one connection at a time, so logging in with the same user can disconnect or stop the algorithm. To run more than one algorithm with IB, open an IB sub-account for each extra algorithm.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### Why does my Interactive Brokers deployment say an existing session was detected?

IB still sees your previous deployment, or another login, as connected. A previous deployment can take a minute to fully disconnect. If you logged in from another location, close that session, and the algorithm restores its data connection on its own.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### Why does my Interactive Brokers deployment fail with "Login failed"?

The credentials are usually incorrect, often because the password has leading or trailing spaces. Paste the password into a text editor to check it. If you can't log in to Trader Workstation, contact IB. If you can log in to Trader Workstation but not the deployment wizard, contact QuantConnect with the algorithm Id and deployment Id.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### How do I fix the Interactive Brokers "account-tasks is required" login error?

Download IB Gateway, run it, and follow its instructions to complete the pending account tasks. Then deploy the algorithm again.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### Why does Interactive Brokers ask for a code card challenge or time out on 2FA?

You didn't reply to the IB Key two-factor request in time. IB triggers the code card "Challenge" window when you don't answer the IBKR Mobile notification. Keep your IB Key device charged and reachable, and see the IB page about missing IBKR Mobile notifications if none arrives.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### What does "Requested market data is not subscribed" mean on Interactive Brokers?

Your algorithm uses the IB data provider without the IB market data subscription it needs. Subscribe to the data bundle at IB, or redeploy with the QuantConnect data provider or the hybrid QuantConnect and IB data providers. If you trade an IB paper account with IB data, share the market data subscription with the paper account in the IB Client Portal.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### What should I do about "Timeout waiting for brokerage response" on Interactive Brokers?

IB didn't respond to an order request. Stop and redeploy the algorithm. On the next deployment, LEAN retrieves the order or the positions it opened or closed.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### What does "No security definition has been found" mean on Interactive Brokers?

Your algorithm added a security that IB considers invalid or unsupported, such as a delisted stock, an expired contract, or a warrant. The algorithm keeps running but doesn't trade that security. To stop deployments that hold such a security, set `self.settings.ignore_unknown_assets` (`Settings.IgnoreUnknownAssets`) to `False`.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### Why is my Interactive Brokers cash balance higher than my tradable cash?

Your account is probably in the IB Insured Bank Deposit Sweep Program. LEAN reads the FDIC Sweep Account Cash as part of your balance, but that cash isn't tradable. LEAN doesn't support accounts in the program, so opt out of it at IB.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### Why can't my algorithm liquidate a fractional Interactive Brokers position?

Dividend reinvestment can leave fractional shares, and LEAN doesn't support fractional trading with IB. For example, a position of 1270.8604 shares can't be liquidated. Turn off automatic dividend election at IB to receive dividends in cash.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### Which IP address do I whitelist for Interactive Brokers?

If you enabled IP restrictions on your IB account, whitelist the QuantConnect address 207.182.16.137.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### What is the Interactive Brokers FIX integration and why would I use it?

The FIX integration routes orders from QuantConnect to your IB account over a FIX session instead of IB Gateway. It stays connected without weekly re-logins, routes trades through a dedicated VPN, and uses less memory. To enable it, subscribe to QuantConnect in the IB Client Portal and register the IBKR QC UID that the deployment wizard shows.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### Can I use the Interactive Brokers FIX integration with an IB paper account?

No, FIX integration doesn't support IB simulated accounts, which start with "DU". You can trade several IB sub-accounts through FIX, but all of them must link to the same QuantConnect account. Wait about 5 minutes after you register an IBKR QC UID before you deploy.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### Why can't I run Interactive Brokers live trading on my Apple Silicon Mac?

The ARM version of the LEAN Docker image doesn't include IB Gateway, so the error "Could not find file '/root/ibgateway/ibgateway'" appears. QuantConnect doesn't support the IB integration on ARM chips such as Apple M1, M2, and M3. Deploy to QuantConnect Cloud instead.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### When does QuantConnect sync my live cash after a deposit or withdrawal?

You can deposit and withdraw cash while your algorithm runs. QuantConnect syncs the algorithm's cash with your brokerage account every day at 7:45 AM Eastern Time (ET). With Charles Schwab, deposited funds are available for API trading 24 hours after the deposit.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### Can I run two algorithms on the same Charles Schwab login?

Not with the same Schwab user. The integration supports one trading account per Schwab user, and deploying a second algorithm for the same user stops the first. To run a second algorithm, authenticate as a different Schwab user and select an account linked to that user.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/charles-schwab>

### How do I connect my Charles Schwab account to a live deployment?

In the deployment wizard, enter your Charles Schwab Login ID, check the Authorization box, and click Authenticate. Log in on the Schwab website, select the accounts to link, and click Allow. Then select the account Id to trade.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/charles-schwab>

### Why does Charles Schwab reject my orders with "No trades are currently allowed"?

Your Schwab account may lack trading permissions for the API. Log in to schwab.com, go to Trade > Trading Platforms, and click Enable trading on thinkorswim. The permissions can take up to 48 hours to apply.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/charles-schwab>

### Why does Charles Schwab reject my liquidation as oversold or overbought?

Schwab counts your open orders that aren't canceled yet, so a liquidation that cancels and closes at the same time looks like double the exposure. Cancel the open orders first, wait about one minute for Schwab to process the cancellations, and then liquidate.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/charles-schwab>

### Why does Charles Schwab reject my stop order price?

The stop price is on the wrong side of the market. A buy stop must sit above the current ask, and a sell stop must sit below the current bid. Compute the stop price from the current quote instead of a stale price.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/charles-schwab>

### Does Charles Schwab limit how many orders my algorithm sends?

Yes, Charles Schwab limits order requests, including place, update, and cancel, to 120 per minute and 4,000 per day. Design your algorithm to send orders sparingly.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/charles-schwab>

### Can I run two algorithms with my tastytrade account?

No, tastytrade only supports authenticating one account at a time per user. If you deploy a second algorithm with tastytrade, the first algorithm stops. The tastytrade API also doesn't support paper trading, so use QuantConnect Paper Trading with the tastytrade brokerage model.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/tastytrade>

### Why does my Alpaca authorization fail?

On the Alpaca website, you must select both your live and paper accounts before you click Allow. If you select only one account, the authorization fails. The algorithm trades the environment you pick in the wizard, regardless of the accounts you authorize.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/alpaca>

### Do I need Alpaca API keys to deploy?

You authorize Alpaca trading through the Authenticate button in the deployment wizard. You only enter an Alpaca API key and secret if you add the Alpaca data provider. Generate the keys on the Alpaca Dashboard page for your real money or paper account.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/alpaca>

### Which order rules does Alpaca apply?

Alpaca combo orders must have 2 to 4 Option legs, and Option orders always use the `DAY` time in force. Only limit orders with the `DAY` time in force can trade outside regular hours. Contingent Equity order sets have up to three orders for one security, and you can update their prices but not their quantities.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/alpaca>

### How do I paper trade with TradeStation?

TradeStation supports paper trading through its simulator after you fund your brokerage account. Select the Simulator environment in the deployment wizard. Alternatively, use QuantConnect Paper Trading.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/tradestation>

### Why does my TradeStation algorithm warn about throttling?

TradeStation enforces rate limits on its REST API, so the integration paces requests and retries after HTTP 429 responses. The first time it throttles a resource group or receives a 429, it sends your algorithm a one-time warning. Throttling can reduce throughput but doesn't fail the request.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/tradestation>

### What credentials do I need for the Public brokerage?

You need a Public secret key and the account number to trade. Apply for API access on the Public API page, and after approval, generate the key under Settings > Security > API on Public.com. Keys have read and write access, and LEAN exchanges the key for short-lived access tokens.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/public>

### Why does Public reject my order with a deposit or pending-order error?

"A deposit of $<amount> is required" means the order costs more than your available buying power. Size the order within your buying power, or set `use_margin` (`UseMargin`) to `True` on a margin account. Public also allows only 10 pending orders that open new positions on the same underlying.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/public>

### How does Public handle good-til-canceled orders?

Public natively supports only day and good-til-date orders. LEAN sends good-til-canceled orders as good-til-date orders that expire 90 days later.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/public>

### What do I need to trade with Clear Street?

You need a Clear Street API key, which acts as your access token, and the numeric ID of the account to trade. Generate the key in the Clear Street web app under Account > API keys. The Clear Street API supports options trading levels 1 and 2 and only market and limit orders for Options.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/clear-street>

### What do I need to trade with Webull?

You need a Webull App Key, App Secret, and the account ID to trade. Apply for API access in the Webull Developer Tools, which takes one to two business days, and then register an application. Disable Quotes for the application, and check Enable 2FA in the wizard if the application keeps two-factor authentication.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/webull>

### How do I set up Binance API keys for QuantConnect?

Create a system-generated HMAC API key and restrict access to the QuantConnect IP addresses 146.59.85.21, 57.128.231.46, and 57.128.233.143. For Binance.US, enter 207.182.16.137. Select Enable Futures if you trade Crypto Futures.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/binance>

### Can I paper trade Binance Crypto Futures or Binance US?

No, paper trading Binance Crypto Futures or Binance US isn't available. You can paper trade Binance spot through the Binance Spot Test Network by selecting the Demo environment. The Binance integrations also don't support sub-accounts, so use your main account.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/binance>

### How do I update an order on Binance?

The Binance and Binance US integrations don't support order updates. Cancel the existing order and place a new order with the new arguments. Binance US and Binance Futures also don't support contingent orders.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/binance>

### Why doesn't Liquidate close my whole crypto balance?

LEAN tracks each currency as its own asset, and a pair like BTCUSD is a virtual position that represents your open trade. When you deploy, LEAN loads each currency into the cashbook but can't recover your virtual pair positions. Liquidating a virtual pair only liquidates the quantity in that pair.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/binance>

### How do I set up Bybit API keys for QuantConnect?

Select API Transaction usage and Read-Write permissions, and enable Unified Trading. Restrict access to the QuantConnect IP addresses 146.59.85.21, 57.128.231.46, and 57.128.233.143. Bybit Demo Trading and the Bybit Testnet each need their own API credentials.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/bybit>

### Why does Bybit say I haven't enabled cross margin trading?

Your Bybit account uses isolated margin. Enable cross margin on the Bybit trading website or app. If Bybit instead reports an invalid API key, check for spaces around the key and secret and confirm the key has read-write permissions.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/bybit>

### Which brokerages can I use to trade crypto futures?

Binance and Bybit support Crypto Futures, and dYdX supports crypto futures. Binance and Bybit restrict access from the United States, so they require the Europe (WAW) region. dYdX isn't available in the United States or to other restricted persons.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages>

### How do I format my Coinbase API private key for the deployment wizard?

For the Cloud Platform, remove the `-----BEGIN EC PRIVATE KEY-----\n` prefix, the `\n-----END EC PRIVATE KEY-----\n` suffix, and the remaining `\n` characters. The LEAN CLI uses the key as it appears in the JSON file. Create the key with the View and Trade permissions.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/coinbase>

### Which Bitfinex wallet does my algorithm connect to?

The account type selects the wallet. `AccountType.CASH` connects to your Exchange wallet, and `AccountType.MARGIN` connects to your Margin wallet. You can't connect to the Funding or Capital Raise wallet, and currencies ending in "F0" don't load into the cashbook.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/bitfinex>

### Does QuantConnect count my Kraken rewards assets?

No, QuantConnect doesn't account for assets you enable in the Kraken rewards program. The Kraken integration also doesn't support order updates or paper trading.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/kraken>

### Why do my Tradier orders outside market hours wait until the open?

The Tradier integration doesn't support extended market hours trading, so it processes orders placed outside regular hours at the market open. To change an order quantity, cancel the order and submit a new one. The Tradier brokerage model also cancels your open orders for a security that performs a reverse split.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/tradier>

### Which data provider should I use with Tradier?

For Equities, use the QuantConnect data provider for real-time data. For Options, use delayed data from the Tradier data provider, and use both providers if you trade both. In the Tradier demo environment, Tradier doesn't stream market data, so you must use the QuantConnect data provider.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/tradier>

### What do I need to use Bloomberg EMSX through Quant Bridge on QuantConnect Cloud?

You need a Trading Firm or Institution organization, a Bloomberg license, and Bloomberg EMSX and EMSX API Server permissions. QuantConnect Cloud only routes orders through the Bloomberg EMSX API Server, which you install on your own cloud server. The EMSX API Server provides no data, so use the QuantConnect data provider.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/bloomberg-emsx>

### How is the Bloomberg FIX connection different from Quant Bridge?

The Bloomberg FIX connection routes orders to the Bloomberg FixNet HUB over FIX, so none of the EMSX API Server setup applies. It requires an Institution organization and a FIX session that Bloomberg provisions. The two connections support the same asset classes but differ in order types, order updates, and account types.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/bloomberg-fix>

### Why do I enter my cash and holdings manually for FIX connections?

FIX connections can't fetch the portfolio state from the brokerage. Enter your cash in the Algorithm Cash State section and your positions in the Algorithm Holdings State section when you deploy. SS&C Eze and Quant Bridge also ask you to set the buying power in the wizard.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/fix-connections>

### Why doesn't the Trading Technologies brokerage model fees and slippage?

Trading Technologies (TT) is an order router with access to many exchanges and brokerages, so its brokerage model has no specific fee or slippage modeling. In live trading, TT reports the total fees after each fill. To backtest with fees and slippage, set a different brokerage model that supports your assets and orders.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/trading-technologies>

### Can EU residents trade with OANDA on QuantConnect?

EU residents can trade with OANDA only through an account with a member of the OANDA Group that offers REST API trading. OANDA Europe Markets moved its accounts to OANDA TMS Brokers, which doesn't offer REST API endpoints for live trading.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/cfd-and-forex-brokerages>

### Why can't I trade some assets that my brokerage integration supports?

Your location or account settings may block them. For example, residents of the EU can't trade US ETFs and can trade the CFD equivalent instead. Check with your local regulators, and adjust your brokerage account settings for the assets you want to trade.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers>

### Can QuantConnect add my brokerage?

QuantConnect can add a brokerage if it has a popular and stable API that the brokerage officially supports. To request a new brokerage, contact QuantConnect.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/unsupported-brokerages>

### Which notifications can a live algorithm send?

Live algorithms can send email, SMS, webhook, and Telegram notifications. The deployment wizard can notify you of order events and insights, and the `notify` (`Notify`) methods send notifications from your code at other moments. LEAN ignores notifications during backtests.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/notifications>

### How many free notifications can my live algorithm send?

The free hourly quota of email, Telegram, and webhook notifications per live algorithm depends on your tier. Quant Researcher allows 20, Team allows 60, Trading Firm allows 240, and Institution allows 3,600. Each notification over the hourly quota costs 1 QCC. Each SMS costs 1 QCC to a US or Canadian number and 10 QCC to other numbers.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/notifications>

### Why didn't I receive my email notification?

Check your junk folders, because email notifications go through your email provider and can be slow. Email notifications hold up to 10 KB of text in the message body. For notifications that don't need internet access, use SMS, which holds up to 1,600 characters.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/notifications>

### How do I set up Telegram notifications?

Create a Telegram group, add a bot, and enter your user Id or group Id in the deployment wizard. You can create a bot with @BotFather or use @quantconnect_notifications_bot. If you use your own bot, also enter its token. The group Id is in the group URL of the Telegram web interface.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/notifications>

### What does a webhook notification send?

A webhook notification is an HTTP POST request to your URL with a 300-second timeout. The JSON payload contains the project name and Id, the insights and order events since the last notification, and the current portfolio holdings. You can add custom headers in the deployment wizard.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/notifications>

### Can I use notifications to send market data to another system?

No, you can't use the notification system for data distribution. Use notifications for alerts about your algorithm, such as order events and insights.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/notifications>

### What happens when my live algorithm throws a runtime error?

The algorithm stops, and QuantConnect emails you. If you enabled automatic restarts, the algorithm tries up to five times to restart. The live results page marks the error on the Meta series of the Strategy Equity chart.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/results>

### Can I close the browser while my live algorithm runs?

Yes, the live trading node runs your algorithm on QuantConnect servers, so closing or refreshing the page doesn't interrupt it. To open the live results page again, open the project and click the Deploy Live icon. The Live Dashboard lists all the live algorithms in your organizations.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/results>

### Which time zone do live logs and orders use?

Live log timestamps use Coordinated Universal Time (UTC). The Orders and Insights tables on the results page show Eastern Time (ET), and the downloaded order and insight files use UTC. Logs stay with the project when you stop and redeploy.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/results>

### Why did my charts lose earlier data when I refreshed them?

Refreshing the charts while the algorithm runs resets the zoom and only shows the data that LEAN sent after the refresh. Charts are sampled every one and ten minutes, so 1-minute custom charts switch to 10-minute sampling after enough samples.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/results>

### How do I share my live results with someone without a QuantConnect account?

In the Share Results section of the live results page, click Make Public. Click Live Stream to get a URL, or Embed Code to get an iframe for a website. Click Make Private to stop sharing.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/results>

### What is the Out of Sample Backtest line on my live equity chart?

QuantConnect runs an out-of-sample (OOS) backtest in parallel with each live deployment. The live results page plots the live equity curve and the OOS backtest curve. If the two curves overlap exactly, the algorithm is perfectly reconciled.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/reconciliation>

### Why are my live trading results different from my backtest?

Backtests model reality closely but not perfectly. Common causes are fill and fee modeling, market impact, data timing, existing holdings, and different start dates. Restarts without saved state and a backtest brokerage model that differs from your live brokerage also cause deviations. Set the backtest brokerage model to your live brokerage and start both with the same cash and holdings.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/reconciliation>

### Why does a condition on an exact time work in backtests but never in live trading?

Backtests inject data at predictable times, while live trading injects data whenever it arrives with microsecond precision. A condition such as "time is 9:30:15" can never match in live trading. Use a time range, a rounded time, or a Scheduled Event.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/reconciliation>

### Why does my custom data arrive at different times in live trading?

In live trading, LEAN polls custom data sources on a schedule that depends on the resolution. LEAN checks daily and hourly sources every 30 minutes, minute sources every minute, and second sources every second. Also timestamp each data point when it was available in reality, so backtests don't read it early.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/reconciliation>

### Why do my indicators differ after a split in live trading?

Backtests use adjusted prices by default, so indicators see a smooth series. In live trading, an indicator holds prices from before and after a split or dividend, so its signals differ. Reset and warm up your indicators when the algorithm receives a corporate event.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/reconciliation>

### How are ticks grouped differently in live trading?

Backtests group ticks into slices that span 1 millisecond. Live trading groups ticks into slices that span up to 70 milliseconds. Make sure your strategy logic works with both slice sizes.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/reconciliation>

### Why do my limit orders fill differently in live trading?

In backtests, limit orders fill as soon as the price reaches the limit. In live trading, your brokerage might fill the order at a different price or not at all, depending on its place in the order book. Backtest orders also fill immediately, while live orders take about half a second at the brokerage.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/reconciliation>

### Does my backtest include short borrowing costs and market impact?

LEAN doesn't model market impact, so large orders can fill better in backtests than in live trading. By default, LEAN also doesn't simulate short borrowing costs. To add them, set a margin interest model, and implement a custom fill model for market impact.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/reconciliation>

### Why don't my live portfolio weights match the backtest on a small account?

Brokerages usually trade whole shares, so a small account can't hit exact allocations such as 10%. Fractional shares help, but not every brokerage supports them. Start the backtest and the live deployment with the same cash to get the closest results.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/reconciliation>

### How can I reduce the risks of live trading?

Live trading carries strategy, portfolio, market, counterparty, operational, and error risks. You can reduce them by diversifying across factors and brokers, monitoring strategy capacity and correlation, and backtesting before deploying. Also keep close access to your email and enable automatic restarts.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/risks>

## Optimization

### What do I need to run a cloud optimization?

You need at least one project parameter and a call to `get_parameter` (`GetParameter`) or the `Parameter` attribute in your code. You also need a successful backtest of the project and QuantConnect Credit (QCC) in your organization. The deployment wizard estimates the cost from your last successful backtest.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/optimization/getting-started>

### How many parameters can I optimize in QuantConnect Cloud?

The cloud optimizer handles up to three parameters. It only supports grid search, which tests every combination, so the number of backtests grows quickly with each parameter. Charts are limited to three dimensions, and more parameters raise the risk of overfitting. To optimize more parameters, run local optimizations with the LEAN CLI.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/optimization/parameters>

### Which optimization strategies are available?

Grid search is the only optimization strategy available. It tests all combinations of parameter values across the range and step size you set. You can contribute other strategies to LEAN through a pull request to the LEAN GitHub repository.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/optimization/strategies>

### Which objectives can the optimizer maximize or minimize?

The optimizer supports the compound annual growth rate (CAGR), drawdown, Sharpe ratio, and Probabilistic Sharpe ratio (PSR) as objectives. To combine several objectives, add constraints, such as Sharpe ratio >= 1, that filter backtests out of the results.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/optimization/objectives>

### How much does a cloud optimization cost?

You rent optimization nodes on a time basis and pay with your organization's QCC. The wizard estimates the cost from your last successful backtest, the number of parameters, and the strategy, but the final cost can differ. Using more nodes doesn't raise the cost on its own, but each backtest has about 15 seconds of spin-up time.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/optimization/deployment>

### Why did my optimization stop before it finished?

The optimization job reads your QCC balance when you submit it and deducts the cost as each backtest completes. It stops when it consumes all the credit. It doesn't use credit you add after submission. Before you deploy, make sure you have enough credit, for example 50% more than the estimate.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/optimization/deployment>

### Do optimizations use my organization's backtesting nodes?

No, a separate cluster of optimization nodes runs optimization jobs. The node type sets the cores, RAM, and maximum cluster size. The O2-8 node suits simple strategies with under 100 assets, O4-12 suits under 500 assets, and O8-16 suits complex strategies and machine learning.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/optimization/deployment>

### How many nodes can an optimization use?

The optimizer runs concurrent backtests on up to 24 nodes, and the maximum cluster size depends on the node type. You choose the node type and the maximum number of nodes in the Estimated Number and Cost of Backtests section when you launch the job.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/optimization/deployment>

### Why do some backtests in my optimization fail?

Backtests in an optimization usually fail because some parameter values cause a divide-by-zero error or an index out-of-range exception. A backtest that throws a runtime error doesn't complete, but you're still charged for it. Check your code handles every value in the parameter range.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/optimization/results>

### Why does my optimization never seem to complete?

If a backtest in the optimization produces more than 700 MB of data, LEAN can't upload the results, so the job appears to never complete. Reduce the amount of data that each backtest produces.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/optimization/results>

### Can I close the browser while an optimization runs?

Yes, the optimization nodes process the job on QuantConnect servers, so you can close or refresh the window. To return to the results, open the project and view all its backtest and optimization results. To stop the job, click Abort in the Status panel.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/optimization/getting-started>

### How do I find stable parameter values in the optimization results?

Look at the parameter charts, which show a heatmap of each objective for two parameters. Zones where adjacent cells have similar colors are stable, because the objective doesn't change much with the parameter values.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/optimization/results>

### How do I export the results of an optimization?

Right-click a row of the Backtests table on the optimization results page and click Export > CSV Export. The table lists the parameter values and objective values of each backtest. Click a row to open that backtest's results page.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/optimization/results>

### Where do I find the optimization Id?

Open the optimization results page and scroll to the table of individual backtest results. The optimization Id is at the top of the table, for example `O-696d861d6dbbed45a8442659bd24e59f`.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/optimization/getting-started>

## Research Pipeline

### What is the Research Pipeline?

The Research Pipeline is a kanban board in the Algorithm Lab for tracking your organization's projects. Each card is one project, and you drag it through the Ideas, Research, Backtest, Paper Trading, and Live Trading stages. To open it, log in to the Algorithm Lab and click the Research Pipeline tab.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/research-pipeline>

### Can AI agents work on Research Pipeline cards?

Yes, you can add pre-configured AI agents that specialize in each stage, or define custom agents. For example, an Ideas Agent adds strategy ideas from recent news, and a Backtest Agent writes algorithms, runs backtests, and optimizes parameters. A Live Monitoring Agent scans news that affects your positions and notifies you.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/research-pipeline>

### How do I see or stop what an agent is doing on a card?

When an agent works on a card, the card shows "Running". Click Running to open the deployment view with the agent's output, and click the stop icon to interrupt it. To see all current and past tasks, open Organization > Agents and click the Tasks tab.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/research-pipeline>

### How many agents can my organization run?

Agents have a fair-use quota but are essentially uncapped. Your team's Agent Nodes set how many agents run concurrently and how many tokens you can use.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/research-pipeline>

### Can I recover a project I archived from the Research Pipeline?

Yes, you can recover projects from the Archived Items section at the bottom of the board at any time. Archive projects that no longer get resources, such as ideas that failed validation or algorithms that lost their edge.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/research-pipeline>

## Object Store

### What is the Object Store?

The Object Store is an organization-wide key-value storage location in QuantConnect Cloud. Every project in the organization can read and write the same keys. Common uses include moving data between backtests and the Research Environment and training machine learning models before you deploy them live.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/object-store>

### Why does my algorithm say it doesn't have permission to write to the Object Store?

The Object Store is only available to paid organizations. In Free organizations, algorithms that save data log "The current user does not have permission to write to the organization Object Store". Upgrade your organization to a paid tier to use it.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/object-store>

### Can I download files from the Object Store?

Only Institution tier organizations that signed the derived data agreement can download Object Store files, and only derived data such as models and signal files. The restriction covers every file, including files you upload, and adding storage doesn't change it. Other organizations see the Download action unavailable, and the API and CLI return "Organization does not have derivative export enabled".

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/object-store>

### Why can't I download my own files from the Object Store?

Section 2.6 of the Terms of Service treats all Object Store contents as QuantConnect data. The platform can't tell your own files from data derived from licensed datasets, so no per-file or per-organization exception exists. Keeping data in the cloud lets QuantConnect offer licensed data at a fraction of its direct cost.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/object-store>

### How do I use my Object Store data if I can't download it?

Read the file in the Research Environment or a backtest and do the analysis there. To keep backtest output, use Download Results, which saves the statistics, equity curves, and order history. Log events and the outcome of data checks without dataset values, because writing dataset values to logs or charts counts as exporting. Project source files don't need the Object Store, so pull them with `lean cloud pull`.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/object-store>

### How much Object Store storage do I get?

Paid organizations get 50 MB and 1,000 files of free storage. You can subscribe to more storage from Organization > Resources > Add Object Store Capacity, which needs storage billing permissions. Storage subscriptions add capacity only and don't enable downloads.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/object-store>

### Why hasn't my Object Store usage updated after I deleted files?

The Object Store page takes about one hour to show the actual usage after you add or remove files. The Resources page shows the total storage of your organization and the storage of each project.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/object-store>

### Can my live algorithm read Object Store files that I update from research?

Yes, a live algorithm can access Object Store changes within minutes, so make sure it can handle a dataset that changes. The live environment reads the Object Store much more slowly than research and backtesting. Keep individual objects under 50 MB to avoid live access issues.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/object-store>

### What is the .assistant folder in my Object Store?

The `.assistant` folder is a reserved folder that QuantConnect AI agents read. Its `skills`, `memories`, and `templates` subfolders hold the instructions, per-project facts, and project scaffolds for your organization. The files are shared across the organization.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/object-store>

## Community

### Why can't I post in the QuantConnect forum?

You must complete 30% of the Bootcamp lessons in the Learning Center to post in the forum. After that, you can create discussions and comment on open discussions.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/forum>

### Why can't I attach my backtest to a forum post?

You can only attach backtests that don't throw errors, from projects you own. To share a backtest that throws an error, call `quit` (`Quit`) before the error occurs. Attaching a backtest shares all the project files used to generate it.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/forum>

### Why does a cloned forum algorithm give different results than the attached backtest?

LEAN is under constant development, so old backtests attached to forum discussions can produce different results today. Data updates can also change results.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/forum>

### How do I edit or delete my forum comment?

You can edit a comment within five minutes after you publish it. To delete a comment, email QuantConnect the comment URL and a reason, and QuantConnect considers the request.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/forum>

### What happens when I accept an answer on my forum discussion?

The discussion closes, and a copy of the accepted answer becomes the first comment. You can only accept answers on discussions you create, and you can only comment on open discussions. To reopen a closed discussion, contact QuantConnect.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/forum>

### Who pays for the QCC rewards I give in the forum?

QCC rewards come out of your organization's QuantConnect Credit balance, so you need enough QCC to give one. Upvotes are free.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/forum>

### How do I get notified about new forum posts?

Follow discussions to get email notifications. To follow all discussions, open the community feed and click Subscribe in the Community Mailing List section. To follow a single discussion, bookmark it.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/forum>

### Where should I report bugs and data issues instead of the forum?

Send bug reports to the Support Team, because the forum isn't an effective bug tracker. Report data issues on the Data Issues list with the specific dates, times, contracts, and type of issue. That system tracks fixes and notifies you when an issue is fixed.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/code-of-conduct>

### Can someone in the community build my algorithm for me?

Before you ask for help, complete Boot Camp and attach a backtest or code snippet with your best attempt. You must know how to code to use QuantConnect. To outsource development, hire an Integration Partner.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/code-of-conduct>

### Can I promote my product in the forum?

No, spam and other promotional activity aren't permitted in the forum. Posts that deliver immediate value are allowed, such as a well-performing algorithm with an attribution to your company in the code comments.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/code-of-conduct>

### What can I do if I lost the device for my two-factor authentication?

Contact QuantConnect. Two-factor authentication for your QuantConnect account uses codes from the Google Authenticator app. You activate and deactivate it in the Security section of your Account page.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/profile>

### How do I get my API token and user Id?

Open yourUsername > My Account, and in the Security section, click Request Email With Token and Your User-Id for API Requests. QuantConnect emails you your user Id and API token.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/profile>

### Why can't I reset my API token?

Stop all your backtests, optimization jobs, live algorithms, and notebooks first. Then open My Account, click Reset My Token in the Security section, and check your email for the new token.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/profile>

### How do I change my account email if I can't access the old address?

The confirmation email goes to your current address, so you need access to it to change the email. For security, support only changes an account email when the request comes from the registered address. If you can't access it, create a new account with an email you control.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/profile>

### What is the difference between deactivating and removing my account?

Deactivating hides your profile and data, such as forum posts, from other members, and you can reactivate by logging in. QuantConnect keeps your data, except backtest results older than 90 days. To remove your account from QuantConnect servers, contact QuantConnect.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/profile>

### Who can apply for an Academic Grant?

Members of an academic or corporate institution with a track record of peer-reviewed quantitative finance papers can apply. You must prove your membership and be actively researching toward a paper that the QuantConnect platform and data can support. The grant gives up to a three-month subscription, and you agree to share your research and code on the forum.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/academic-grants>

### What are Integration Partners?

Integration Partners are vetted independent consultants and companies that offer guidance, teaching, and algorithm development services. They set their own prices and services. To join, contact QuantConnect and pass the test, and you keep 100% of the revenue from members who hire you.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/integration-partners>

### How much does the QuantConnect Affiliate Program pay?

Affiliates receive 10% of sales from their referrals each month, for up to 12 months after the referred customer subscribes. If the customer leaves earlier, you receive payments for the months they stayed. Apply through the application form.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/affiliates>

### How do I get my research published on the Research page?

Submit a proposal first, and QuantConnect emails you whether it's approved. QuantConnect only approves research based on a financial concept, theory, or model, and rejects combinations of technical indicators with overfit parameters. After approval, add your findings and a backtest or notebook that follows the content guidelines.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/research>

### How does the Strategies leaderboard rank strategies?

The Leaderboard ranks strategies by their three-month returns each day and shows the one-year Sharpe ratio and the score. The score is the one-year Sharpe ratio with a penalty for strategies with less than a year of out-of-sample data. For example, a strategy with six months of out-of-sample data has its Sharpe ratio halved.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/strategies>

### Why do my published strategy's results differ from my original backtest?

QuantConnect backtests the strategies on the Strategies page every day, so the results include out-of-sample data. The submission guidelines also require a backtest over the last 5 years, default reality models, and no cash deposits or withdrawals.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/strategies>

### Can I change the strategy name and description that Mia generates?

Yes, Mia generates the Strategy Name and Strategy Description fields when you publish a strategy, and you can edit both before you click Publish. Describe what changed in each version in the Version Notes field.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/community/strategies>

## API Reference

### How do I authenticate requests to the QuantConnect API?

Send an `Authorization` header with HTTP Basic credentials and a `Timestamp` header with the current Unix time. The Basic credentials are the base64 encoding of `<userId>:<hash>`, where the hash is the SHA-256 hex digest of `<apiToken>:<timestamp>`. The timestamp makes every signature different, so you never send the API token itself.

```python
from base64 import b64encode
from hashlib import sha256
from time import time

def get_headers(user_id, api_token):
    timestamp = f'{int(time())}'
    hashed_token = sha256(f'{api_token}:{timestamp}'.encode('utf-8')).hexdigest()
    authentication = b64encode(f'{user_id}:{hashed_token}'.encode('utf-8')).decode('ascii')
    return {'Authorization': f'Basic {authentication}', 'Timestamp': timestamp}
```

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/api-reference/authentication>

### Why does the QuantConnect API return a 401 authentication error?

The API returns 401 when the key is missing or invalid, or when the timestamp is too old for the hash. Check your user Id and API token, and generate a fresh hash with the current timestamp for each request. Also make sure your system clock is correct. Call the `/authenticate` endpoint to test your hashing.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/api-reference/authentication>

### What is the base URL of the QuantConnect API?

The base URL is `https://www.quantconnect.com/api/v2`. Requests use POST with a JSON body.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/api-reference/authentication>

### Where do I find my organization Id for API requests?

Open Organization > Home and check the URL. For example, the organization Id of `https://www.quantconnect.com/organization/5cad178b20a1d52567b534553413b691` is `5cad178b20a1d52567b534553413b691`.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/api-reference/authentication>

### Why does the live orders endpoint not show my latest orders?

The live read endpoints return snapshots. The portfolio state, orders, trades, and insights snapshots update about every 10 minutes, and the logs snapshot updates about every 5 minutes. For real-time events, send notifications from your algorithm.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/api-reference/live-management/read-live-algorithm/orders>

### Can I export data through the backtest logs endpoint?

No, per the Terms and Conditions, you may not use the backtest logs endpoint to export market data or any other dataset information. Log quotas also limit the logs that you can produce.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/api-reference/backtest-management/read-backtest/logs>

### Why does the Object Store get endpoint return "Organization does not have derivative export enabled"?

Downloading Object Store files is only available to Institution tier organizations that signed the derived data agreement, and only for derived data. Without that permission, the `/object/get` endpoint returns this error. Read the data in the Research Environment or a backtest instead.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/api-reference/object-store-management/get-object-store-file>

### Why must I lock a project before editing it through the API?

Locking a project lets you edit it when the project has collaborators or when an LLM edits files for you through the MCP Server. Call the lock project endpoint before you update files in these cases.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/api-reference/project-management/collaboration/lock-project>

### How do I apply a code change to a project file through the API?

Use the `/files/patch` endpoint with a patch in unified diff format, such as the output of `git diff`. A patch can change one or more files in the project.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/api-reference/file-management/patch-file>

### How do I let a project choose its backtest node automatically through the API?

Call the update project nodes endpoint without any nodes. All the nodes become inactive, and `autoSelectNode` becomes true. If you pass nodes, the endpoint activates those nodes.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/api-reference/project-management/nodes/update-project-nodes>

### How do I run a parameter sweep through the API?

Use the optimization endpoints. The `/optimizations/create` endpoint launches an optimization job with your parameters, and `/optimizations/estimate` estimates its execution time first.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/api-reference/optimization-management/create-optimization>

### What must I include when I report a data issue through the API?

Every report needs a reproducible case. Attach a project with a backtest or notebook that shows the issue, or include a code snippet in the content property. Before you report, call `/community/data-library/issues/list` to check if an open issue already covers the same ticker and resolution.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/api-reference/data-issues/create-data-issue>

### Can I open a support ticket through the API?

Yes, if you have a support seat in the organization. Provide a subject and message to open a ticket, which uses one ticket from your seat quota, or a ticket hash and message to comment. A comment on a closed ticket reopens it unless you set `close` to true, and attachments only work on new tickets.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/api-reference/support/create-support-ticket>

### How do I read my QCC balance through the API?

Call the account endpoint. It returns the organization account status, including the organization Id, QCC balance, and credit card information.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/api-reference/account>

### Is there a complete example of the QuantConnect API?

Yes, a Python research notebook implements every QuantConnect API endpoint in plain Python to show the JSON calls and responses. QuantConnect also hosts the API in Postman, where you can try requests and view responses.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/api-reference/examples>
