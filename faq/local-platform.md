# Local Platform FAQ

This file answers common questions about QuantConnect Local Platform, and each answer links to the full docs page.

## Key Concepts

### What is QuantConnect Local Platform?

Local Platform is a Visual Studio Code extension that lets you create, debug, and run QuantConnect projects on your own machine. It syncs your projects with QuantConnect Cloud, so you can use local tools and still deploy to the cloud. LEAN runs inside Docker containers on your machine.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/key-concepts/getting-started>

### How do I create my first project and backtest on Local Platform?

Install Local Platform, open VS Code, and click **Login to QuantConnect** in the Initialization Checklist panel. Pull an organization workspace, click **Create Project**, and enter a name. To run it, click **Build** and then **Backtest** in the top-right corner of VS Code.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/key-concepts/getting-started>

### What is the difference between Local Platform and the LEAN CLI?

Local Platform is a graphical interface inside VS Code, and the LEAN CLI is a command-line tool. Local Platform uses the LEAN CLI underneath, and both share your login credentials. If you log in with one, you are logged in for both.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/authentication>

### Which organization tier do I need for Local Platform features?

Each of the five organization tiers has its own set of Local Platform features. Anonymous projects, which never sync to QuantConnect Cloud, require the Institution tier. Offline use for up to 24 hours requires the Trading Firm or Institution tier. You can change your organization tier at any time.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/key-concepts/features>

### What is a deployment target in Local Platform?

The deployment target sets where a project runs: your local machine or QuantConnect Cloud. In the Project panel, click the **Deployment Target** field and select **Local Platform** or **Cloud**. Local targets show blue icons, and cloud targets show gold icons.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/key-concepts/deployment-targets>

### Why does my backtest still run in the cloud after I sync my projects locally?

Syncing your organization workspace doesn't change where a project runs. The deployment target of the project decides that. Set the **Deployment Target** field in the Project panel to **Local Platform** to run on your own hardware. Gold icons mean the project targets QuantConnect Cloud.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/key-concepts/deployment-targets>

### What are the differences between the Local Platform and Cloud Platform deployment targets?

The local target uses your on-premises data, hardware, and management team. The cloud target uses the Dataset Market, QuantConnect Cloud compute, and hardware that the QuantConnect team manages. Both targets let you import custom datasets.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/key-concepts/deployment-targets>

### Does Local Platform include QuantConnect data for local backtests?

Local Platform only includes sample data from the LEAN repository. Local backtests use licensed data that you download and maintain yourself, while cloud backtests use data that QuantConnect provides and maintains. Your proprietary data stays on your premises when you run locally.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/key-concepts/features>

### Can I use my own IDE, version control, and custom LEAN builds with Local Platform?

Yes. Local Platform lets you track project files with your own git systems and build and run custom versions of LEAN. The Cloud Platform uses a cloud-hosted VS Code and stores past project files with your backtest results. Both platforms support autocomplete and debugging.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/key-concepts/features>

### Can I run live trading on Local Platform as reliably as in the cloud?

Local live trading depends on the stability of your own setup. QuantConnect Cloud runs live algorithms in a stable, co-located environment. To avoid downtime from power outages or crashes, deploy to QuantConnect Cloud.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/key-concepts/features>

## Installation

### What are the system requirements for Local Platform on Windows?

Windows systems need a 64-bit processor, 4 GB of RAM, and a 60 GB hard drive or more. They also need Windows 10 version 1903 or higher and hardware virtualization enabled in the BIOS.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/installation/install-on-windows>

### What are the system requirements for Local Platform on macOS?

Mac systems need hardware from 2010 or newer with an Intel processor and macOS 10.14 or newer. They also need 4 GB of RAM and a 60 GB hard drive or more.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/installation/install-on-macos>

### What are the system requirements for Local Platform on Linux?

Linux systems need 4 GB of RAM or more and a 60 GB hard drive or more. You must also install Docker.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/installation/install-on-linux>

### Why does Local Platform require Docker?

Local Platform runs LEAN inside Docker containers. Each container holds a minimal Linux operating system, the LEAN engine, and all the packages available on QuantConnect.com. This setup avoids installing dependencies on your computer.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/installation/install-on-windows>

### How do I install Local Platform?

Install Docker and then run `docker pull quantconnect/lean` in a terminal. While the image downloads, install Visual Studio Code, the QuantConnect extension, and the Python language support extension. If VS Code asks you to log in to QuantConnect, the installation succeeded.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/installation/install-on-windows>

### How long does it take to install Local Platform?

The extension takes about 10 minutes to install. The latest LEAN Docker image takes about 1 hour to download, so continue the other installation steps while it downloads.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/installation/install-on-windows>

### Does Local Platform update the LEAN Docker image automatically?

Yes. When you use Local Platform, it automatically pulls the latest LEAN image if your current image is more than one week old.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/installation/install-on-windows>

### Do I need an internet connection to use Local Platform?

You need an internet connection to download updates, collaborate with team members, and sync projects with QuantConnect Cloud. Trading Firm and Institution organizations can run local backtests and research notebooks offline for up to 24 hours.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/installation/install-on-windows>

### Why doesn't Docker start when I run Local Platform on Windows?

By default, Docker Desktop doesn't start when your computer starts, so you must start it manually before you use LEAN. To start it automatically, open Docker Desktop, click **Settings > General**, and enable **Start Docker Desktop when you log in**.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/installation/install-on-windows>

### What do I do if Docker says the WSL 2 installation is incomplete?

Select the **Enable WSL 2 Features** check box when you install Docker Desktop. After you restart your computer, follow the instructions in the Docker dialog to finish the WSL 2 installation. Then run `wsl --update` in PowerShell with administrator privileges.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/installation/install-on-windows>

### Why can't Local Platform detect my Docker installation?

Local Platform can miss Docker when it isn't in the expected location. Set the **Executable Path: Docker** extension setting to the path of your Docker executable. To open the setting, click **File > Preferences > Settings** and then **Extensions > QuantConnect**.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/installation/install-on-macos>

### Why can't Local Platform sync my workspace on Windows?

Windows controlled folder access can block `lean.exe`. Search for "Ransomware protection" in the Start Menu and click **Allow an app through Controlled folder access**. Then click **Add an allowed app > Recently blocked apps** and allow `lean.exe`.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/installation/install-on-windows>

### What do I configure if Docker uses the legacy Hyper-V backend on Windows?

With the Hyper-V backend, you must enable file sharing for your temporary directory and organization workspace. In Docker settings, go to **Resources > File Sharing**. Add `C:/Users/<username>/AppData/Local/Temp` and your workspace path, and then click **Apply & Restart**.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/organization-workspaces>

## Development Environment

### How do I log in to Local Platform?

Log in to the Algorithm Lab, start Docker Desktop, and open VS Code. Click the QuantConnect icon, click **Login to QuantConnect** in the Initialization Checklist panel, and then click **Grant Access**. The checklist also confirms the LEAN CLI is installed and Docker is running.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/authentication>

### How do I log out of Local Platform or switch accounts?

In VS Code, press F1, enter `QuantConnect: Logout of QuantConnect`, and press Enter. Local Platform and the LEAN CLI share credentials, so logging out or in affects both tools.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/authentication>

### What is an organization workspace?

An organization workspace is a local directory that holds a data directory, a `lean.json` configuration file, and your project files for one organization. You can keep a separate workspace for each organization you belong to. LEAN needs the data directory and configuration file to run locally.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/organization-workspaces>

### How do I pull my cloud projects into Local Platform?

Click the QuantConnect icon and then click **Pull Organization Workspace** in the Select Workspace panel. Select the organization and create a directory for the workspace. Local Platform builds the initial file structure in a few minutes and then pulls your cloud project files.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/organization-workspaces>

### How do I switch to a different organization workspace?

Close any open project and then click **Change** in the Open Project panel. Pull the cloud workspace of the organization you want to use.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/organization-workspaces>

### What files and directories does an organization workspace contain?

The `data` directory holds local data, starting with sample data from the LEAN repository. The `storage` directory holds local Object Store data, and `lean.json` holds the LEAN configuration. Projects are added as directories, and shared libraries go in a `Library` directory.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/organization-workspaces>

### Why does each organization workspace have its own data directory?

Each organization has its own data licenses. A separate data directory per workspace keeps the data you download under one organization's licenses with that organization.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/organization-workspaces>

### How do I change the Local Platform extension settings?

In VS Code, click **File > Preferences > Settings** and then **Extensions > QuantConnect**. The settings include the Docker and LEAN CLI executable paths, the workspace path, the sync mode, and the preferred language for new projects.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/configuration>

### How do I point Local Platform to a different LEAN CLI installation?

Set the **Executable Path: Lean** extension setting to the path of the LEAN CLI executable you want to use. To open the setting, click **File > Preferences > Settings** and then **Extensions > QuantConnect**.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/configuration>

### Which LEAN settings do I have to edit manually in lean.json?

Local Platform sets most `lean.json` values for you. Edit these keys manually: `show-missing-data-logs`, `initialization-timeout`, `algorithm-manager-time-loop-maximum`, `maximum-warmup-history-days-look-back`, `maximum-chart-series`, `maximum-data-points-per-chart-series`, and `data-monitor-store-succeeded-requests`. The file accepts single-line and multiline comments.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/configuration>

### How do I give my local algorithm more time to initialize?

Increase the `initialization-timeout` key in the `lean.json` file of your organization workspace. The value is in seconds, and the default is 300.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/configuration>

### Why doesn't autocomplete work for QuantConnect classes in VS Code?

Your Python interpreter might not have the latest QuantConnect stubs. Press F1, run **Python: Select Interpreter**, and note the starred interpreter path. Then run `<pythonExecutablePath> -m pip install quantconnect-stubs --upgrade` in a terminal.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/autocomplete>

### How do I use autocomplete in Local Platform?

Type the first few characters of a variable, function, class, or member and then press Ctrl+Space. Select a member from the list to complete it. Autocomplete covers the default LEAN class members, but it doesn't support class names or user-defined objects.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/autocomplete>

### How do I add a collaborator to a project in Local Platform?

Open the project, click the QuantConnect icon, and click **Add Collaborator** in the Collaborate section. Select a member or your entire organization, which counts as one collaborator slot. Select **Live Control** to let them manage live deployments, and then click **Add User**.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/collaboration>

### How many collaborators can I add to a project?

The quota depends on your organization tier. Free and Quant Researcher tiers don't support collaborators. Team and Trading Firm tiers allow 10 collaborators per project, and the Institution tier allows unlimited collaborators.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/collaboration>

### Why can't my collaborator see the shared library in my project?

Adding a collaborator to a project doesn't give them access to its shared libraries. Add them as a collaborator to the library project too.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/collaboration>

### Why are my project files locked when I collaborate?

Only one collaborator has edit control at a time. To edit, click **Request Edit Control** in the Collaborate section of the Project panel. After you take control, the panel shows **You have edit permission** and the lock icon leaves the file tabs.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/collaboration>

### How do I remove myself from a project I don't own?

Delete the project from your workspace. Owners remove other members by clicking their profile image in the Collaborate section and then clicking **Remove User**.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/collaboration>

### Which LEAN version does Local Platform run?

By default, Local Platform runs the latest master branch of the LEAN GitHub repository. The latest version is generally the safest because it includes all bug fixes. To pick another branch, open the project and click the **LEAN Engine** field in the Project panel.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/lean-engine-versions>

### Does changing the LEAN engine branch affect all my projects?

No. Changing the LEAN engine branch only affects the current project. New projects use the master branch by default.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/lean-engine-versions>

### How do I run a custom version of LEAN on Local Platform?

Build a custom LEAN Docker image and run it through the Local Platform interface. Custom images let you add extensions or fixes to LEAN. The LEAN CLI Custom Docker Images page explains how to build and use them.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/projects/custom-docker-images>

### How does Local Platform sync my projects with QuantConnect Cloud?

Local Platform syncs your local project files with QuantConnect Cloud automatically. Each time you save a file, it saves the change locally and in the cloud version of the project. Anonymous projects are the exception and never sync.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/synchronization>

### Do I need to pull my workspace again after I create a new project?

No. The default **Synchronize All Projects** mode syncs all your cloud and local projects in the background while the extension runs. You only pull a workspace once to set it up.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/configuration>

### How do I stop Local Platform from syncing my projects with the cloud?

Change the **Sync: Local And Cloud Projects** extension setting. Choose **Synchronize Open Project Only** to sync only the open project, or **Disable Synchronization** to stop syncing entirely.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/synchronization>

### How do I prevent conflicts when an AI agent edits my local project files?

Set the **Sync: Local And Cloud Projects** extension setting to **Disable Synchronization**. This mode stops local agent edits from conflicting with the cloud versions of your projects.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/synchronization>

### Which file types does Local Platform push to QuantConnect Cloud?

Local Platform only pushes `.py`, `.cs`, and `.ipynb` files. Other files in your project, such as JSON, CSV, and HTML files, stay on your machine.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/synchronization>

### What are anonymous projects?

Anonymous projects are local projects that never sync with QuantConnect Cloud. They let your organization keep full ownership of project security. Only members of Institution organizations can create them.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/synchronization>

### How do I see and stop the nodes Local Platform is using?

Open a project and click the QuantConnect icon. The Resources panel at the bottom of the Project panel lists backtest, research, and live nodes, and the **In Use By** column names the project. Click the stop button next to a node to stop it.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/resource-management>

### How do I choose a Python library environment for my project?

Open the project, click the QuantConnect icon, and select an environment in the **Python Foundation** field of the Project panel. Then import the library at the top of your file. Environments exist because not all Python libraries are compatible with each other.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/packages-and-libraries>

### How long does it take to add a new library to QuantConnect?

Adding a new library takes 2 to 4 weeks. QuantConnect reviews each request because libraries run on its servers and must be secure. You can see libraries under review on the LEAN GitHub Issues list.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/packages-and-libraries>

### How do I change the default language for new projects?

Set the **User: Preferred Language** extension setting to `Py` for Python or `C#` for C#. New projects then contain `.py` or `.cs` files to match.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/working-with-vs-code>

### Where do I see my algorithm logs and errors in VS Code?

The **Cloud Terminal** tab shows API messages, errors, and algorithm logs. The **Problems** tab highlights coding errors, and the **Terminal** tab is a command line in your project directory. The **Ask Mia** tab opens the AI assistant.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/development-environment/working-with-vs-code>

## Private Cloud

### What is the Local Platform Private Cloud?

Private Cloud is a Local Platform add-on that runs work from a distributed team on a cluster of central servers. Quants use thin clients such as laptops, and the servers hold GPUs and a central copy of proprietary data. Results stream back to the Local Platform interface.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/private-cloud>

### What do I need to use a Private Cloud?

You need an Institutional subscription to Local Platform with the Private Cloud add-on. The add-on is licensed per server and supports up to 10 servers. See the Pricing page for costs.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/private-cloud>

### How do I set up a Private Cloud?

On the master server, install and log in to the LEAN CLI and run `lean private-cloud start --master`. To add servers, run `lean private-cloud start --slave` on each one. On each quant's computer, select the Private Cloud deployment target and enter the master server address.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/private-cloud>

## Projects

### Where are my Local Platform projects stored?

Your projects are directories inside your organization workspace directory. To view all your projects, open that directory on your machine.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/projects/getting-started>

### How do I open a project in Local Platform?

Click the QuantConnect icon, close any open project, and click **Open Project**. Select a project in your organization workspace and click **Select**. If an **I Trust the Authors** button appears, click it.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/projects/getting-started>

### How do I find the Id of a local project?

Open the `<organizationWorkspace>/<projectName>/config.json` file. The project Id is the value of the `local-id` or `cloud-id` key.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/projects/getting-started>

### How do I add parameters to a project in Local Platform?

Open the project and click **Add New Parameter** in the Project panel. Enter a unique name and a default value, and then click **Create Parameter**. Read the value in your algorithm with `self.get_parameter` (`GetParameter`).

Docs: <https://www.quantconnect.com/docs/v2/local-platform/projects/getting-started>

### Which file types can a Local Platform project contain?

Projects support `.py` and `.cs` code files, `.ipynb` notebook files, and `.html` and `.css` files for custom reports. Projects also hold `.json` configuration files, which the Explorer panel hides.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/projects/files>

### Are local backtest results synced to QuantConnect Cloud?

No. Local backtests, optimizations, and live deployments save their results as files in the project directory. Local Platform doesn't push these result files to QuantConnect Cloud.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/projects/files>

### Which directory names can't I use in a project?

The following directory names are reserved: `.ipynb_checkpoints`, `.idea`, `.vscode`, `__pycache__`, `bin`, `obj`, `backtests`, `live`, `optimizations`, `storage`, and `report`.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/projects/files>

### How do I create a shared library in Local Platform?

Open a project, click **Add Library** in the Project panel, and click **Create New**. Enter a name and click **Create Library**. Local Platform adds the template files to a new project in the `Library` directory of your organization workspace.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/projects/shared-libraries>

### When should I move code into a shared library?

Add code to a library when it's stable and several projects need it. Every project that uses a library picks up your edits to it. Keep work in progress and single-project code in the project itself.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/projects/shared-libraries>

### How do I put my Local Platform projects under git version control?

Create a repository for your organization workspace and clone it to a temporary directory. Move the `.git` directory into the workspace and delete the temporary directory. Then run `lean cloud pull`, `git add` your project and `Library` directories, commit, and push.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/projects/version-control>

## Datasets

### Do I need to buy data to run backtests on Local Platform?

You need local data to run algorithms and research on Local Platform. The workspace starts with sample data from the LEAN repository. For more data, buy a Download license in the Dataset Market or connect your own data or provider.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/datasets/getting-started>

### What can I do with data I download from the Dataset Market?

A Download license allows internal LEAN use by the licensed organization only. You can't redistribute the data or convert it to another format. You may share chart images if the original data can't be reconstructed from them.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/datasets/downloading-data>

### How much does downloading data for Local Platform cost?

The cost depends on the dataset and is calculated per file or per day. You can download by ticker, which costs less for a few tickers, or in bulk to avoid selection bias. Bulk downloads offer historical packages and daily updates, and you usually need both.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/datasets/downloading-data>

### Where does Local Platform store downloaded data?

Downloaded data goes in the `data` directory of your organization workspace, where LEAN reads it. To use another directory, change the `data-folder` key in the `lean.json` configuration file. Choose a folder that is fast and has plenty of space.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/datasets/getting-started>

### How much disk space does a dataset need?

Open the Download in Bulk page and click a dataset. The **Size and Format** section shows its approximate size.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/datasets/getting-started>

### What format is local LEAN data stored in?

LEAN stores data in flat CSV or JSON files and compresses them with zip. Each asset class has its own data format page.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/datasets/getting-started>

### Which live data providers can I use with local live algorithms?

Local live algorithms can use a brokerage data provider, IQFeed, or Polygon. Historical data requests in local algorithms come from your data vendor, so review its quotas and limits.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/datasets/getting-started>

### Can I use Alpha Vantage data on Local Platform?

Yes, for historical US Equity price data in research, backtests, and optimizations. You need a free or premium Alpha Vantage API key. Alpha Vantage isn't supported for local live trading.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/datasets/alpha-vantage>

### Why are my prices unadjusted when I use Alpha Vantage or Polygon data?

Alpha Vantage and Polygon only provide raw price data. To get adjusted Equity prices, download the US Equity Security Master with the CLI.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/datasets/polygon>

### Can I use universe selection with Polygon or Alpha Vantage data on Local Platform?

Yes, if you download the universe selection data from the Dataset Market. The dataset listings show the CLI commands. For live trading, download new universe files every trading day, which you can automate with Python scripts.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/datasets/polygon>

### How do I backtest on Local Platform with Polygon or Alpha Vantage data?

First run the backtest locally with the LEAN CLI. The CLI caches all the data the backtest requests on your hard drive. Then you can run the backtest or optimization with the Local Platform interface. For research, start the Research Environment with the CLI first.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/datasets/polygon>

### How much historical data does the Polygon data provider give me?

The history available for history requests and warm-up depends on your Polygon data plan. See the Simple Pricing page on the Polygon website for each plan.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/datasets/polygon>

### How often do I update local data for live trading with Polygon?

If you trade US Equities or Equity Options, update your US Equity Security Master periodically. Weekly updates are enough in most cases. If your algorithm uses universe data, download the latest files every trading day.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/datasets/polygon>

## Backtesting

### How do I run a backtest on Local Platform?

Open a project and click the blue **Backtest** icon for a local run or the gold icon for a cloud run. If the build succeeds, "Received backtest <backtestName> request" displays and the results page opens in a new tab. If the launch fails, the tab shows the error.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/backtesting/getting-started>

### Can I close VS Code while a local backtest runs?

Yes. You can close Local Platform and the Docker Desktop window without stopping a local backtest. Keep Docker running, because quitting Docker Desktop stops the backtest.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/backtesting/getting-started>

### How do I find the Id of a local backtest?

The backtest Id is on the first line of the backtest log file. Local backtest Ids are numbers such as 1710698424, and cloud backtest Ids are hexadecimal strings.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/backtesting/getting-started>

### How many local backtests can I run at the same time?

You can run as many concurrent backtests as your CPU and RAM can handle. Each backtest runs in a separate container, called a virtual node, on the same host machine. The Resources panel lists your virtual nodes.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/backtesting/deployment>

### How do I stop a running local backtest?

Stop the backtesting node in the Resources panel. Click the stop button next to the node.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/backtesting/deployment>

### Where are local backtest results saved?

Local Platform saves results in `<organizationWorkspace>/<projectName>/backtests/<unixTimestamp>`. The directory holds a copy of the code, the `<backtestId>.json` result file, logs, order events, and data monitor reports. Only you can view local backtest results.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/backtesting/results>

### How do I find which data files my local backtest couldn't load?

Open the `failed-data-requests-*.txt` file in the backtest result directory. It lists up to 100,000 local data paths that LEAN failed to load. To also log successful paths, set `data-monitor-store-succeeded-requests` to `true` in `lean.json`.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/backtesting/results>

### Where are the logs of a local backtest?

Local Platform saves the log file automatically when the backtest completes. Open `<organizationWorkspace>/<projectName>/backtests/<unixTimestamp>/<algorithmId>-log.txt`. The `log.txt` file in the same directory holds the syslog. Log timestamps use your algorithm time zone.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/backtesting/results>

### How do I raise the chart limits for local backtests?

Local chart quotas come from the `maximum-chart-series` and `maximum-data-points-per-chart-series` keys in `lean.json`. The defaults are 30 series and 1,000,000 data points per series. Edit these keys to change the limits.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/backtesting/results>

### Where is the backtest report saved for a local backtest?

On the results page, click the **Report** tab and then **Download Report**. For local backtests, the report is saved as `report.html` and `report.pdf` in the backtest result directory. To customize it, run `lean report` with the `--html` or `--css` option.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/backtesting/results>

### Why are timestamps different in the orders table and the orders CSV?

The Orders Summary table shows timestamps in Eastern Time. The downloaded orders CSV uses Coordinated Universal Time (UTC). For local backtests, the CSV is saved as `orders.csv` in the backtest result directory.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/backtesting/results>

### Can I read local backtest orders with read_backtest_orders?

No. The `read_backtest_orders` (`ReadBacktestOrders`) method and the `/backtests/orders/read` endpoint only work for algorithms deployed in QuantConnect Cloud. For local backtests, read the `<algorithmId>-order-events.json` file in the backtest result directory.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/backtesting/results>

### Can I debug cloud projects with the Local Platform debugger?

No. Local debugging only works with local projects. Set the project deployment target to Local Platform, add at least one breakpoint, and click the **Debug** icon.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/backtesting/debugging>

### What do I need to install to debug on Local Platform?

For Python, install version 2023.4.0 of Microsoft's Python VS Code extension. For C#, install .NET and Microsoft's C# VS Code extension.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/backtesting/debugging>

### Why do my backtests run faster in QuantConnect Cloud than locally?

QuantConnect Cloud hosts special hardware that is optimized for LEAN. Local backtest speed depends on your own hardware.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/backtesting/troubleshooting>

### Why do my local backtest results differ from my cloud backtest results?

Differences usually come from data. An outdated US Equity Security Master misses splits and dividends, which change adjusted historical prices. Update your local Security Master every day and download the same datasets you use in the cloud.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/backtesting/troubleshooting>

## Research

### How do I open a research notebook on Local Platform?

Every new project contains a notebook file. Open the project, click the Explorer icon, expand **QC (Workspace)**, and click `research.ipynb`. The notebook uses the hardware and data of the project's deployment target.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/research/getting-started>

### What do I do if my notebook shows a Select Kernel button and won't run cells?

The notebook didn't connect to the correct kernel. Click **Select Kernel**, choose **QuantConnect Research Server Collection**, and pick the first Jupyter server. Then select `Foundation-Py-Default` for Python or `Foundation-C#-Default` for C#.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/research/deployment>

### How do I stop a research node on Local Platform?

Close the notebook file first. In the Resources panel, click the stop button next to the research node, and then click **Yes**.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/research/getting-started>

### Why is my notebook output gone when I reopen it?

Saving with Ctrl+S keeps the cell content. If you stop the node or close the notebook, the output remains when you reopen it. If you stop the node and close the project, you must run the cells again.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/research/deployment>

### How many research notebooks can I run at once on Local Platform?

You can run as many concurrent local notebooks as your CPU and RAM can handle. Each notebook runs in a separate container on the same host machine.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/research/deployment>

## Optimization

### What do I need before I optimize parameters on Local Platform?

Your project needs at least one algorithm parameter and a `get_parameter` (`GetParameter`) call or `Parameter` attribute. It also needs a successful backtest.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/optimization/getting-started>

### How many parameters can I optimize at once in Local Platform?

The optimization page supports up to three parameters. To optimize more parameters, run local optimizations with the LEAN CLI.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/optimization/getting-started>

### Can I close Local Platform while a local optimization runs?

Yes. You can close Local Platform and the Docker Desktop window as a local optimization runs. Keep Docker running, because quitting Docker Desktop interrupts the backtests.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/optimization/getting-started>

### Where are local optimization results saved?

Open `<organizationWorkspace>/<projectName>/optimizations/<optimizationName>`. The directory holds a copy of the code, a directory for each backtest, the syslog, `optimizer-config.json`, and `optimization-result-<optimizationId>.json`.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/optimization/getting-started>

## Live Trading

### What are the risks of running live algorithms on my own machine?

Local live algorithms are exposed to downtime from power outages, computer crashes, and natural disasters. To avoid these risks, deploy your live algorithms to QuantConnect Cloud.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/live-trading/getting-started>

### How do I update the code of a running live algorithm?

You can't change code or parameter values while a live algorithm runs. Stop the algorithm, make your changes, and redeploy it. Stopping keeps your portfolio holdings.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/live-trading/getting-started>

### Why did my live algorithm stop after I placed a manual order in my brokerage account?

LEAN stops live algorithms when it detects interference outside the algorithm's control. This prevents race conditions between you and the algorithm. To adjust holdings, stop the algorithm, place your trades, and redeploy.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/live-trading/getting-started>

### How do I liquidate all positions of a live algorithm?

Open the live results page, click **Liquidate**, and click **Liquidate** again. The algorithm sends market orders for open markets and market on open orders otherwise. Then it stops.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/live-trading/getting-started>

### Does a live algorithm keep its state when it restarts?

No. Restarts, including automatic restarts and redeployments, don't save the state of your algorithm. See State Persistence to learn what a restart recovers and how to persist the rest.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/live-trading/getting-started>

### How do I change parameter values in a live algorithm without redeploying?

Add a scheduled event that downloads a remote file and updates the parameter values from its contents. In Python, call `self.download` (`Download`) inside a function you schedule with `self.schedule.on` (`Schedule.On`).

Docs: <https://www.quantconnect.com/docs/v2/local-platform/live-trading/getting-started>

### How do I clear the results history of a live project?

Live results persist between deployments of the same project. To start fresh, clone the project and deploy the cloned version.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/live-trading/getting-started>

### Where are the result files of a local live algorithm?

Open `<organizationWorkspace>/<projectName>/live/<timeStamp>`. The directory holds a copy of the code, the `L-<deploymentId>.json` results, sampled chart files, the live log, the syslog, and a config file.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/live-trading/getting-started>

### How do I find the deployment Id of a live algorithm?

Open the log file and search for "Launching analysis for". The log shows every deployment Id for the project, so use the most recent one. Local deployment Ids look like `L-3554110262`.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/live-trading/getting-started>

## Object Store

### Where does Local Platform save Object Store data?

Local Object Store data is saved under the `storage` directory of your organization workspace. Each key becomes a file in `<organizationWorkspace>/storage/<projectName>`. To read another project's data, include that project's Id in the key.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/object-store>

### Is the Object Store shared across projects?

Yes. The Object Store is shared across the entire organization. You can use the same key to access data from any project in the organization.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/object-store>

### How do I save a machine learning model in the Object Store?

The Object Store stores strings and bytes. Encode binary objects, such as a trained model, to a supported type like base 64 before you save them. Use `self.object_store.save` (`Save`) or `self.object_store.save_bytes` (`SaveBytes`).

Docs: <https://www.quantconnect.com/docs/v2/local-platform/object-store>

### Why is Object Store access slow in my live algorithm?

Live algorithms access the Object Store much more slowly than research and backtests do. Keep each object under 50 MB to prevent access issues in live trading.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/object-store>

### How do I delete Object Store data on Local Platform?

Call `self.object_store.delete` (`Delete`) with the key. You can also delete the files from the `storage` directory of your organization workspace.

Docs: <https://www.quantconnect.com/docs/v2/local-platform/object-store>
