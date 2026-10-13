# LEAN CLI FAQ

This file answers common questions about the LEAN CLI, the tool that runs LEAN locally and syncs projects with QuantConnect Cloud. Each answer links to the full docs page.

## Key Concepts

### What do I need before I can use the LEAN CLI?

You must be a member of a QuantConnect organization on a paid tier. The CLI also needs pip, which comes with Python, and the commands that run LEAN locally need Docker installed and running. To run backtests, optimizations, or research locally, you also need the data your algorithm uses on your machine.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/key-concepts/getting-started>

### Do I need a paid QuantConnect subscription to run the LEAN CLI on my own computer or server?

Yes. To use the LEAN CLI, you must be a member of an organization on a paid tier, even when every command runs locally. To compare the tiers and their prices, see the pricing page.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/organizations/tier-features>

### What is the difference between LEAN and the LEAN CLI?

LEAN is the open-source algorithmic trading engine. The LEAN CLI is the recommended way to run LEAN on your local machine, and it runs LEAN inside Docker containers. The CLI can do almost everything LEAN can do, except run some programs in the LEAN ToolBox. If you need another ToolBox program, run LEAN manually and move the output data into the CLI's data directory.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/key-concepts/getting-started#06-LEAN-vs-LEAN-CLI>

### Why does the CLI say "No such command" or "No such option"?

The command or option doesn't exist in your installed version of the CLI. If the documentation lists it, your CLI is probably outdated. Run `pip install --upgrade lean` to update to the latest version.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/key-concepts/troubleshooting#02-Common-Errors>

### Why does the CLI say "Please make sure Docker is installed and running"?

Commands that run the LEAN engine locally always run it in a Docker container. Start Docker if it's installed. If Docker is already running, run the command again with the `--verbose` flag to see more details. If the virtual environment has a non-standard Docker path or no Docker access, uninstall and reinstall Docker.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/key-concepts/troubleshooting#02-Common-Errors>

### Why does the CLI say "This command requires a Lean configuration file"?

The command needs a `lean.json` file, and the CLI searches the current directory and its parent directories for one. Run the command from your organization workspace directory, which `lean init` creates. Alternatively, pass the path to the file with the `--lean-config` option.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/key-concepts/troubleshooting#02-Common-Errors>

### Why does the CLI say "We couldn't find you account in the given organization"?

The organization Id in your `lean.json` file is incorrect. To fix the error, run `lean init` in an empty directory to create a new organization workspace for the correct organization.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/key-concepts/troubleshooting#02-Common-Errors>

### Why does the CLI say a path is not valid on Windows?

Windows doesn't allow the characters `<`, `>`, `:`, `"`, `|`, `?`, and `*` in path components. It also reserves the names CON, PRN, AUX, NUL, COM1 to COM9, and LPT1 to LPT9, in any letter case. Path components also can't start or end with a space or end with a period. Rename the project or workspace directory to remove these patterns.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/key-concepts/troubleshooting#02-Common-Errors>

### How do I fix "Mounts denied" or "bind source path does not exist" errors on macOS?

Docker's file sharing settings don't allow the directories that the CLI shares with the container. In Docker, open **Settings > Resources > File Sharing**. Add `/private/var/folders` and either `/Users` or the path to your organization workspace.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/key-concepts/troubleshooting#02-Common-Errors>

### How do I fix "Docker wants access to <path>" on Windows?

You're running Docker with the legacy Hyper-V backend without the required file sharing. In Docker, open **Resources > File Sharing**. Add `C:/Users/<username>/AppData/Local/Temp` and your organization workspace directory, and then click **Apply & Restart**.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/key-concepts/troubleshooting#02-Common-Errors>

### How do I fix "Could not open '/lib64/ld-linux-x86-64.so.2'"?

Docker pulled the wrong platform version of the LEAN image. Run `docker rmi quantconnect/lean` to remove the image. Then run `docker pull quantconnect/lean --platform=linux/amd64` to pull the AMD64 version.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/key-concepts/troubleshooting#02-Common-Errors>

### How do I fix a "No such file" Docker error?

Reset the Docker socket setting. In Docker Desktop, open the advanced settings and disable **Allow the default Docker socket to be used**. Save and restart Docker, then enable the setting again and restart Docker once more.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/key-concepts/troubleshooting#02-Common-Errors>

### How do I report a LEAN CLI bug?

Open an issue in the QuantConnect/lean-cli repository on GitHub. Before you open it, run the failing command with the `--verbose` flag and paste the output into the issue. Mask any sensitive information, such as credentials.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/key-concepts/troubleshooting#03-Report-Issues>

## Installation

### How do I install the LEAN CLI?

Install pip and Docker, and then run `pip install --upgrade lean`. On Windows, run the command in PowerShell as an administrator. To check whether the CLI is already installed, run `lean --version`.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/installation/installing-lean-cli>

### Which Python distribution should I use for the LEAN CLI?

We recommend Anaconda, because it contains many of the packages available when LEAN runs, which helps local autocomplete. On Macs with Apple chips, install Miniforge because Anaconda doesn't support Apple chips. The Python distribution from the Microsoft Store isn't supported.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/installation/installing-pip>

### What are the system requirements for running Docker with the LEAN CLI?

Windows needs a 64-bit processor, 4 GB RAM, Windows 10 version 1903 or later, hardware virtualization enabled in the BIOS, and a 60 GB drive. On Windows, enable the WSL 2 features when you install Docker Desktop. Macs need 4 GB RAM and a 60 GB drive.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/installation/installing-lean-cli#02-Install-Docker>

### Why must I start Docker manually before I run LEAN on Windows?

Docker Desktop doesn't start automatically when your computer starts. To start it automatically, open Docker Desktop, click **Settings > General**, and enable **Start Docker Desktop when you log in**.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/installation/installing-lean-cli#02-Install-Docker>

### How do I update the LEAN CLI and its Docker images?

Run `pip install --upgrade lean` to update the CLI, which also checks for new versions once a day. Commands that run LEAN, such as `lean backtest`, `lean optimize`, and `lean research`, check for a new Docker image once a week. To update the image immediately, run the command with the `--update` flag.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/installation/installing-lean-cli#05-Stay-Up-To-Date>

### How do I uninstall or reinstall the LEAN CLI completely?

Run `pip uninstall lean`. For a full uninstall, also delete the `.lean` directory in your home directory and your organization workspaces. Your local data lives in the `data` directory of each workspace, so keep a copy of it if you plan to reinstall.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/installation/installing-lean-cli#06-Uninstall>

## Initialization

### Where do I find the user Id and API token for `lean login`?

Request them on your QuantConnect account page, and we email both values to your registered email address. Then run `lean login` and enter them when prompted, or pass them with the `--user-id` and `--api-token` options.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/initialization/authentication#02-Log-In>

### Where does the LEAN CLI store my credentials?

`lean login` saves your user Id and API token in the `credentials` file of the global configuration directory. The directory is `C:\Users\<username>\.lean` on Windows, `/Users/<username>/.lean` on macOS, and `/home/<username>/.lean` on Linux. Update these files through the CLI only.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/initialization/configuration#02-Global-Configuration>

### Why does the CLI say "Invalid credentials, please log in using `lean login`"?

The command communicates with the QuantConnect API and you haven't authenticated yet. Run `lean login` and enter your user Id and API token. To see which account is logged in, run `lean whoami`.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/key-concepts/troubleshooting#02-Common-Errors>

### What is an organization workspace?

An organization workspace is a local directory that connects to one of your organizations in QuantConnect Cloud. `lean init` creates it with a `data` directory, a `storage` directory for the Object Store, and a `lean.json` configuration file. Each organization has its own workspace because each organization has its own data licenses.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/initialization/organization-workspaces>

### Where should I run LEAN CLI commands?

Run them in the root of your organization workspace, the directory where you ran `lean init`. Running them there keeps the directory structure consistent when you synchronize projects with the cloud. It also lets the CLI find the `lean.json` file automatically.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/initialization/organization-workspaces>

### How do I create a workspace for a different organization?

Open a terminal in an empty directory, run `lean init`, and select the organization to link. To set the default language of new projects at the same time, run `lean init --language python` or `lean init --language csharp`.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/initialization/organization-workspaces#02-Create-Workspaces>

### Which `lean.json` settings must I edit by hand?

The CLI updates most values of `lean.json`. A few settings require manual edits, such as `initialization-timeout`, `algorithm-manager-time-loop-maximum`, `maximum-chart-series`, `maximum-data-points-per-chart-series`, and `show-missing-data-logs`. The file is JSON and supports single-line and multiline comments.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/initialization/configuration#03-Lean-Configuration>

## Datasets

### Where does the LEAN CLI store local data, and can I move it?

The default data directory is the `data` folder in your organization workspace. To change it, set the `data-folder` property in `lean.json`, and the CLI resolves relative paths from the directory of `lean.json`. The data directory is the only local directory mounted into the Docker containers, so put every local file your algorithm reads there.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets/format-and-storage>

### Is the sample data from `lean init` enough to backtest my strategy?

No. The `data` directory that `lean init` creates contains a small amount of sample data for each asset class to show the file format. To backtest a real strategy locally, get data from the Dataset Market, a brokerage, a third-party provider, or your own files.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets/format-and-storage#02-Default-Location>

### Why does the CLI change the market hours and symbol properties files?

Every day, the CLI updates `data/market-hours/market-hours-database.json` and `data/symbol-properties/symbol-properties-database.csv`. To disable these updates, set the `file-database-last-update` value in `lean.json` to a date in the future.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets/format-and-storage#04-Data-Updates>

### Why are the prices in my downloaded US Equity files so large?

US Equity, Equity Options, and Index Options files store prices multiplied by 10,000, so $644.80 is stored as 6448000. LEAN divides the values by 10,000 when it reads the files. The files of other asset classes store the actual prices.

Docs: <https://www.quantconnect.com/docs/v2/lean-engine/data-format/key-concepts#03-Price-Representation>

### Can I test algorithms locally without buying data?

Yes. Run `lean data generate` to create realistic fake data with a Brownian motion model. For example, `lean data generate --start 20150101 --symbol-count 10` generates dense minute Equity data for 10 random symbols. The generator supports Equity, Forex, CFD, Future, Crypto, and Option data.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets/generating-data>

### How do I read a custom data file from my computer in a local backtest?

Copy the file into the data directory of your organization workspace. In the `get_source` (`GetSource`) method of your custom data class, build the path with `Globals.data_folder` (`Globals.DataFolder`) and return a `SubscriptionDataSource` with `SubscriptionTransportMedium.LOCAL_FILE`. Use forward slashes in the path because backslashes don't work.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets/custom-data>

### Must I pay for data to run LEAN locally if QuantConnect Cloud data is free?

Yes, for QuantConnect data. Cloud backtests use the Dataset Market data in QuantConnect Cloud, and local runs need the data files on your machine. You buy local QuantConnect data through the CLI with QuantConnect Credit (QCC) or a data subscription.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets/quantconnect/key-concepts>

### What's the difference between downloading a dataset in bulk and by ticker?

A bulk download is an annual subscription to the full dataset with daily updates for one year, and it includes every data type. After that year, subscribe to the cheaper updates package to keep it current. A by-ticker download charges QCC per file for only the tickers, dates, and data types you select.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets/quantconnect/key-concepts#02-Data-Type>

### How do I download a dataset I bought from the Dataset Market?

Open the dataset listing, click the **CLI** tab, fill in the Command Line Generator, and run the command in your organization workspace. Alternatively, run `lean data download` and follow the interactive wizard. If a listing has no **CLI** tab, you can't download that dataset.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets/quantconnect/key-concepts#03-Using-the-CLI>

### Does the US Equity Security Master include price data?

No. The US Equity Security Master contains splits, dividends, and symbol changes. Price data comes from the separate US Equities dataset, which depends on the Security Master. Subscribe to the Security Master on your organization's pricing page before you download US Equities data.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets/quantconnect/us-equity#02-Prerequisites>

### Why does `lean data download` say my organization needs an active Security Master subscription?

Many datasets depend on the US Equity Security Master for splits, dividends, and symbol changes. If you download one of them without an active Security Master subscription, the CLI returns this error. Subscribe to the US Equity Security Master on your organization's pricing page, which requires billing permissions.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets/quantconnect/key-concepts#03-Using-the-CLI>

### Which datasets must I download to backtest US Equity Options locally?

You need the US Equity Security Master, the US Equity Option Universe, and the underlying US Equities data, plus the US Equity Options price data. The universe dataset provides the available contracts and their daily Greeks and implied volatility.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets/quantconnect/equity-options#02-Prerequisites>

### Which datasets must I download to backtest US Futures locally?

You need the US Futures Security Master to build continuous contracts and the US Future Universe for the available contracts. You also need the US Futures price data for each data type your algorithm uses: trade, quote, and open interest.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets/quantconnect/futures#02-Prerequisites>

### Must I download trade, quote, and open interest data?

The data types aren't mandatory, and you can run backtests with only trade data. To reproduce the same results as QuantConnect Cloud, download every data type the dataset provides. Run a separate by-ticker command for each data type.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets/quantconnect/key-concepts#02-Data-Type>

### How much does downloading QuantConnect data for local use cost?

By-ticker downloads cost a fixed amount of QCC per file, and the CLI shows the file count and total price before you confirm. Bulk downloads are annual subscriptions priced by organization tier and resolution. Each dataset page in the docs lists the prices and a worked example.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets/quantconnect/us-equity#06-Price>

### Am I charged again if `lean data download` stops halfway and I run it again?

You pay only for files the CLI downloads, and you aren't charged for files it didn't download before it stopped. Before it downloads, the CLI checks whether the files already exist on your machine. If local data exists, the CLI asks whether to overwrite it, and the `--overwrite` flag enables overwriting.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/api-reference/lean-data-download>

### How do I keep my bulk data up to date?

After a bulk download, run the same `lean data download` command with a new date range. To automate the updates, schedule a Python script in your data directory. US Equities updates are available at 7 AM Eastern Time after each trading day, and they require an updates subscription. Each dataset page includes an example update script.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets/quantconnect/us-equity#03-Download-in-Bulk>

### Can I run `lean data download` in a script without prompts?

Yes. Pass the `--dataset` option and all the options the dataset needs, and the command runs in non-interactive mode. Add the `--yes` flag to confirm payment prompts automatically.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/api-reference/lean-data-download>

### Can I use data downloaded with the CLI outside of LEAN or share it?

No. The CLI API Access and Data Agreement doesn't permit you to display or distribute the data. You can use it only for individual or internal employee use. The data comes in LEAN format, and you can't manipulate it for transmission or use in other applications.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets/quantconnect/key-concepts#03-Using-the-CLI>

### Can I use my brokerage as the historical data source for local runs?

Yes. Pass `--data-provider-historical <brokerageName>` and the brokerage's required options to `lean backtest`, `lean research`, `lean optimize`, or `lean data download`. The CLI saves the options in `lean.json`, so later runs reuse them. To view the asset classes your brokerage serves, see its brokerage page.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets/brokerages>

### Why are my local Equity prices unadjusted when I use a brokerage or third-party data provider?

Brokerages and third-party providers such as Polygon and Theta Data serve raw data. To get adjusted Equity data in local deployments, download the US Equity Security Master. To get continuous Futures data, download the US Futures Security Master.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets/brokerages#03-Supported-Datasets>

### Why doesn't universe selection work locally with Polygon or another third-party data provider?

Third-party providers don't include QuantConnect's universe files. In local deployments, download the universe datasets you use from the Dataset Market. Examples are the US Equity Coarse Universe, US ETF Constituents, and US Equity Option Universe. For live trading, download new universe data periodically, which you can automate with Python scripts.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets/polygon#04-Universe-Selection>

### Does alternative data I licensed from QuantConnect work with other data providers?

Yes. If you licensed alternative data from QuantConnect, it works with the brokerage and third-party data providers for research, backtesting, and live trading. With FactSet, it works for research and backtesting.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets/brokerages#05-Alternative-Data>

### Which third-party data providers can I use with the LEAN CLI?

The CLI supports historical data from Alpha Vantage, Databento, FactSet, IQFeed, Polygon, Terminal Link, and Theta Data. Each provider needs its own account or API key, and you pay the provider directly. IQFeed works only on Windows, and Terminal Link requires an organization on the Institution tier.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/datasets>

## Projects

### How do I create a new project with the LEAN CLI?

Open a terminal in your organization workspace and run `lean project-create --language python "<projectName>"` or `lean project-create --language csharp "<projectName>"`. The command creates the algorithm file, a research notebook, a `config.json` file, and editor configuration. To skip the `--language` option, run `lean config set default-language python`.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/projects/project-management#02-Create-Projects>

### What characters can a project name contain?

A project name can contain letters, numbers, spaces, hyphens, and underscores. It can't start with a space or be a reserved name such as CON, PRN, AUX, NUL, COM1 to COM9, or LPT1 to LPT9. To create a project in a subdirectory, include forward slashes in the name.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/projects/project-management#04-Rename-Projects>

### Does `lean project-delete` also delete the project in QuantConnect Cloud?

Yes. `lean project-delete` deletes the project on your local machine and in QuantConnect Cloud. If you're a collaborator on the project, the command removes you as a collaborator and keeps the project for the other collaborators.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/projects/project-management#05-Delete-Projects>

### How do I find a project's Id?

Open the `config.json` file in the project directory and read the `cloud-id` or `local-id` value. The CLI sets both values automatically, so don't edit or remove them.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/projects/project-management#07-Get-Project-Id>

### What's the difference between `lean cloud pull` and `lean cloud push`?

`lean cloud pull` copies projects from QuantConnect Cloud to your organization workspace. `lean cloud push` uploads your local projects to QuantConnect Cloud and creates cloud projects when necessary. Both commands act on every project unless you pass `--project "<projectName>"`.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/projects/cloud-synchronization>

### Does `lean cloud push` delete files in the cloud that I don't have locally?

Yes. When you push a project that has a cloud copy, the CLI deletes cloud files that don't exist locally. Your local configuration values also overwrite the cloud values. The CLI pushes only supported file types.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/api-reference/lean-cloud-push>

### Does `lean cloud pull` delete local files that aren't in the cloud?

No. When you pull a project that has a local copy, local files that don't exist in the cloud stay in place. The configuration values of the cloud project overwrite the local values. If a local directory with the same path isn't linked to the cloud project, the CLI skips that project and logs a warning.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/api-reference/lean-cloud-pull>

### Why did `lean cloud push` add a "1" to my project name?

A cloud project with the same path already exists, and it isn't linked to your local project through an earlier pull or push. To avoid overwriting it, the CLI appends a 1 to the name of your local project before it pushes.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/api-reference/lean-cloud-push>

### How do I push a project when the cloud project has a lock conflict?

Run `lean cloud push --project "<projectName>" --force`. The `--force` flag pushes the project even if there's a lock conflict.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/api-reference/lean-cloud-push>

### How do I rename a project that exists locally and in the cloud?

Rename the project directory in your organization workspace, and then run `lean cloud push --project "<newName>"`. The CLI renames the cloud project to match. Alternatively, rename the project in QuantConnect Cloud and then pull it.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/projects/project-management#04-Rename-Projects>

### Why do my pulled cloud projects have errors or no autocomplete locally?

QuantConnect Cloud adds some imports automatically, but local runs don't. Add `from AlgorithmImports import *` to the top of every Python file. For C# files, copy the list of `using` statements from the Imports section of the autocomplete page.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/projects/autocomplete#07-Imports>

### How can my algorithm detect whether it runs locally or in the cloud?

Compare the machine's hostname with your computer's hostname. In Python, check `platform.node()`, and in C#, check `Environment.MachineName`. If the value matches your computer's name, the algorithm runs locally.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/projects/cloud-synchronization#04-Detecting-Environment>

### Can I develop locally and run backtests in the cloud?

Yes. Write code in your local editor and run `lean cloud backtest "<projectName>" --push --open` to push changes, backtest in the cloud, and open the results. This workflow uses QuantConnect's infrastructure and data, so you don't need your own data. It needs an internet connection to communicate with the cloud.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/projects/workflows#02-Cloud-focused-Workflow>

### Can I run unit tests with the LEAN CLI?

You can't run traditional unit test frameworks with the LEAN CLI. Instead, put your testable logic in separate classes or functions and run assertions during algorithm initialization in a backtest. Skip the tests in live mode.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/projects/workflows#05-Unit-Testing>

### How do I encrypt my project files?

Create a text file with a key of at least 32 characters, and then run `lean encrypt "<projectName>" --key "<keyPath>"`. To push an encrypted copy to the cloud, run `lean cloud push --project "<projectName>" --encrypt --key "<keyPath>"`. Encryption isn't available for projects that have collaborators.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/projects/encryption>

### Can an encrypted project use a library?

Yes, if the library is unencrypted or encrypted with the same key as the project. An encrypted project can't use a library encrypted with a different key.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/projects/encryption#40-Libraries>

### What does the project `config.json` file store?

It stores the project description, parameters, cloud and local Ids, organization Id, language, libraries, encryption state, Docker options, LEAN engine version, and Python environment. Parameter keys and values must be strings. The file is JSON without support for comments.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/projects/configuration>

### How do I expose a port or set environment variables for the LEAN container?

Add a `docker` property to the project's `config.json` file with `environment` and `ports` values. For example, `"docker": { "ports": { "999": "6006" } }` exposes host port 999 to internal port 6006.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/projects/configuration#02-Properties>

### How do I set up local autocomplete for Python?

In VS Code, install the Python and Pylance extensions and open the project directory that the CLI generated. In PyCharm, install the `quantconnect-stubs` package in the project interpreter. To update autocomplete for new LEAN releases, run `pip install --upgrade quantconnect-stubs`.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/projects/autocomplete>

### Which libraries can I use in local algorithms?

On AMD64 systems, the CLI supports the same C# and Python libraries as QuantConnect Cloud. ARM64 systems support a shorter list. To use another library or version locally, run `lean library add "<projectName>" <packageName>`. The command adds a PyPI package to `requirements.txt` or a NuGet package to the `.csproj` file.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/projects/libraries/third-party-libraries>

### How do I share code between projects?

Create a project library in the `Library` directory of your workspace with `lean project-create "Library/<libraryName>"`. Then run `lean library add "<projectName>" "Library/<libraryName>"` to link it to each project. In Python, import it with `from <libraryName>.<fileName> import <memberName>`.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/projects/libraries/project-libraries>

### Can I use my own Docker image or a modified version of LEAN?

Yes. Run `lean config set engine-image <imageName>` and `lean config set research-image <imageName>` to use custom images for the engine and research environment. To build images from your own LEAN repository, run `lean build`. To return to the official images, run `lean config unset engine-image` and `lean config unset research-image`.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/projects/custom-docker-images>

### How do I use Git with my QuantConnect projects?

Turn your organization workspace into a Git repository. Clone your new repository into a temporary directory and move its `.git` directory into the workspace. Then run `lean cloud pull`, add the project directories and `Library` folder, commit, and push.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/projects/version-control>

## Research

### How do I start a local Research Environment?

Open a terminal in your organization workspace and run `lean research "<projectName>"`. JupyterLab starts on port 8888 and opens in your browser. Use `--port` to choose another port and `--no-open` if you work in PyCharm or VS Code.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/research#02-Start-the-Research-Environment>

### Where does the local Research Environment get its data?

By default, it reads data from your local machine. To use data from QuantConnect Cloud, a brokerage, or a third-party provider, add the `--data-provider-historical` option. With `--download-data`, the research session buys and downloads the files it needs with QCC.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/research#02-Start-the-Research-Environment>

### How do I open a local research notebook in VS Code or PyCharm?

Start the environment with `lean research "<projectName>" --no-open`. In VS Code, open the notebook, click **Select Kernel**, choose **Existing Jupyter Server**, and enter `http://localhost:8888/`. In PyCharm, add `http://localhost:8888/?token=` as a configured Jupyter server.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/research#05-Use-VS-Code>

### How do I analyze local or cloud backtest results in a research notebook?

Local backtest results are JSON files in `<projectName>/backtests/<timestamp>`, which you can open from the research environment. If you logged in with `lean login`, the notebook also has an `api` object. Use it to call `api.read_backtest` (`ReadBacktest`) for cloud backtests.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/research#06-Retrieving-Local-Backtests>

## Backtesting

### How do I run a local backtest?

Open a terminal in your organization workspace and run `lean backtest "<projectName>"`. LEAN runs in the `quantconnect/lean` Docker image, which contains the same libraries as QuantConnect Cloud, and uses your local data. The results go to `<projectName>/backtests/<timestamp>`, or to the directory you pass with `--output`.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/backtesting/deployment#02-Run-Local-Backtests>

### Why do my local backtest results differ from my cloud backtest results?

Cloud backtests use the Dataset Market data in QuantConnect Cloud, and local backtests use the data on your machine. If your local data is different or incomplete, the results differ. For Equity and Equity Option backtests, download the US Equity Security Master, and for Futures, download the US Futures Security Master.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/backtesting/deployment#02-Run-Local-Backtests>

### How can a local backtest download the data it needs automatically?

Run `lean backtest "<projectName>" --download-data`. The backtest buys missing files from QuantConnect with QCC, stores them in your data directory, and re-downloads multi-day files older than 7 days. The setting persists in `lean.json`, and `--data-provider-historical Local` switches back.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/backtesting/deployment#04-Download-Datasets-During-Backtests>

### How do I stop an automatic data download from spending too much QCC?

Add the `--data-purchase-limit <value>` option to `lean backtest` or `lean research`. Without it, the data provider keeps downloading until your organization runs out of QCC. The limit applies only to the command you pass it to.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/backtesting/deployment#04-Download-Datasets-During-Backtests>

### Why can't my local algorithm connect to a service on localhost?

LEAN runs in a Docker container, so `localhost` points to the container instead of your computer. Use `host.docker.internal` instead. For example, connect to `http://host.docker.internal:<port>/`.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/backtesting/deployment#02-Run-Local-Backtests>

### How do I run a local backtest with an older LEAN version?

Find the tag you want on the `quantconnect/lean` Docker Hub tags page. Then run `lean backtest "<projectName>" --image quantconnect/lean:<tag>`. To use the local image without pulling updates, add `--no-update`.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/backtesting/deployment#02-Run-Local-Backtests>

### How do I pass parameters to a backtest from the command line?

Add one `--parameter <name> <value>` option for each parameter. For example, `lean backtest "My Project" --parameter ema_fast 100 --parameter ema_slow 50`. The `lean cloud backtest` command accepts the same option.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/backtesting/deployment#02-Run-Local-Backtests>

### Where do I find the logs of a local backtest?

The logs are in `<projectName>/backtests/<timestamp>/<algorithmId>-log.txt`. To print the most recent backtest logs in the terminal, run `lean logs`, and add `--live` or `--optimization` for other run types.

Docs: <https://www.quantconnect.com/docs/v2/writing-algorithms/logging#07-Get-Logs>

### How do I debug a Python algorithm locally?

For VS Code, run `lean backtest "<projectName>" --debug debugpy` and then run the **Debug with Lean CLI** configuration. For PyCharm Professional, add the `pydevd-pycharm` package and run `lean backtest "<projectName>" --debug pycharm`. Open the project directory itself in your editor.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/backtesting/debugging>

### Why does PyCharm say "Connection to Python debugger failed" after debugging?

PyCharm shows this message after a Python debugging session with the LEAN CLI finishes. You can ignore it. Local Python debugging in PyCharm requires PyCharm Professional, because it uses the remote debugging feature.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/backtesting/debugging#02-Python-and-PyCharm>

### How do I debug a C# algorithm locally?

Run `lean backtest "<projectName>" --debug vsdbg` for Visual Studio or VS Code, or `--debug rider` for Rider. Wait until the CLI says to attach, and then attach your editor's debugger to the container.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/backtesting/debugging>

## Live Trading

### Can I run live algorithms on my own machine with the LEAN CLI?

Yes. Run `lean live deploy "<projectName>"` and follow the wizard to select a brokerage and data provider. The algorithm runs in a Docker container, and the results go to `<projectName>/live/<timestamp>`. To deploy to QuantConnect Cloud instead, run `lean cloud live deploy "<projectName>" --push --open`.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/api-reference/lean-live-deploy>

### Which datasets do I need for local live trading?

For US Equities, download the US Equity Security Master to get adjusted prices. For options and futures, also download the matching universe datasets, such as the US Equity Option Universe or US Future Universe. Crypto, Forex, and CFD algorithms don't need these datasets.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/live-trading/brokerages/quantconnect-paper-trading#02-Deploy-Local-Algorithms>

### Can I deploy a live algorithm with Interactive Brokers on a Mac with an Apple chip?

No. QuantConnect doesn't support the Interactive Brokers integration on ARM chips such as Apple M1, M2, and M3. The ARM version of the LEAN image doesn't include IB Gateway, so the deployment fails with "Could not find file '/root/ibgateway/ibgateway'".

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/live-trading/brokerages/interactive-brokers#09-Troubleshooting>

### Can I connect a local live algorithm to IB Gateway running on my desktop?

Yes. Run `lean live deploy` with `--ib-host "host.docker.internal"` and `--ib-port` set to the IB Gateway socket port. You then log in to IB Gateway yourself and handle its restarts. To run several deployments, start one IB Gateway on a different port for each deployment.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/live-trading/brokerages/interactive-brokers#04-Deploy-with-External-IB-Gateway>

### Can I combine several live data providers?

Yes. Select several data providers in the deployment wizard, or separate them with commas in `--data-provider-live`. The order defines precedence, so LEAN uses the second provider only for securities the first doesn't cover.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/live-trading/data-providers/polygon#06-Mutiple-Data-Providers>

### How do I place manual orders in a local live algorithm?

Run `lean live submit-order "<projectName>"` with the ticker, market, security type, order type, and quantity. You can also add securities with `lean live add-security`, change orders with `lean live update-order`, and cancel them with `lean live cancel-order`. Each order applies to a single security.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/live-trading/algorithm-control#02-Control-Local-Algorithms>

### How do I send a custom command to a running live algorithm?

Run `lean live command "<projectName>" --data "<payload>"` for local algorithms or `lean cloud live command` for cloud algorithms. The payload runs your algorithm's `on_command` (`OnCommand`) method. To run a `Command` class, add a `$type` key with the class name, and write `` `$type `` in PowerShell.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/live-trading/algorithm-control>

### How do I stop or liquidate a live algorithm from the CLI?

For local algorithms, run `lean live stop "<projectName>"` or `lean live liquidate "<projectName>"`. For cloud algorithms, run `lean cloud live stop "<projectName>"` or `lean cloud live liquidate "<projectName>"`. To check a cloud deployment's status, run `lean cloud status "<projectName>"`.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/live-trading/algorithm-control>

## Reports

### How do I generate a backtest report with the LEAN CLI?

Open a terminal in your organization workspace and run `lean report`. The command creates a report of the most recent backtest in `./report.html`. Use `--backtest-results <path>` to select another backtest and `--report-destination <path>` to change the output file.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/reports#02-Generate-Reports>

### Can I show live results in a LEAN CLI report?

Yes. Pass the path to a live results JSON file with the `--live-results <path>` option. The report shows the live results over the backtest results.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/reports#02-Generate-Reports>

## Optimization

### How do I optimize parameters locally with the LEAN CLI?

Add the parameters to the `parameters` property of the project's `config.json` file and read them with `self.get_parameter` (`GetParameter`). Then run `lean optimize "<projectName>"` and choose the strategy, target, parameter ranges, and constraints in the wizard. The results go to `<projectName>/optimizations/<timestamp>`.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/optimization/deployment#02-Run-Local-Optimizations>

### Can I run an optimization in QuantConnect Cloud from the CLI?

Yes. Run `lean cloud optimize "<projectName>" --push` to push the project and start the optimization wizard in the cloud. Cloud optimizations use the Dataset Market data and an optimization node type you select, so you don't need local data or a powerful machine.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/optimization/deployment#03-Run-Cloud-Optimizations>

## Object Store

### Where is the local Object Store?

The local Object Store is the `storage` directory of your organization workspace. Add files to it directly or with the save methods in your algorithm. Run `lean object-store ls` to open the directory in your file explorer.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/object-store#02-Local-Storage>

### How do I upload files to the cloud Object Store from the CLI?

Run `lean cloud object-store set <key> <localFilePath>`. If the upload fails, your organization might not have enough storage space. To upload a whole directory, loop over its files in a script that calls the same command.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/object-store#03-Cloud-Storage>

### Why does `lean cloud object-store get` say "Organization does not have derivative export enabled"?

Downloading Object Store files is available only to Institution tier organizations that signed QuantConnect's derived data agreement. The restriction covers every file, including files you upload or generate. Read the files in the Research Environment or a backtest instead, and pull project source files with `lean cloud pull`.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/object-store#04-Download-Files>

### How quickly do live algorithms see Object Store changes?

A live algorithm can access the data within minutes of a change to the Object Store, so make it handle a changing dataset. Live access to the Object Store is slower than in research and backtesting. Keep individual objects under 50 MB to prevent access issues.

Docs: <https://www.quantconnect.com/docs/v2/lean-cli/object-store#06-Live-Trading-Considerations>
