# AI Assistance FAQ

This file answers common questions about QuantConnect AI features, from Mia and Agents to the MCP server. Each answer links to its full docs page.

## Getting Started

### What AI tools does QuantConnect offer?

QuantConnect offers three AI tools. Mia is an agentic coding assistant in the Cloud IDE. Agents are AI specialists for each stage of strategy research. The MCP server connects external AI clients to the QuantConnect API. All of them run on QuantConnect Agent servers, which wrap the model in the tools, data, and project context of quant research.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/getting-started>

### What is the difference between Mia and the MCP server?

Mia is a QuantConnect agent that runs on QuantConnect servers and works inside your project from the Ask Mia panel or the Research Pipeline. The MCP server lets a third-party AI client, such as Claude Code, Copilot, or Cursor, call the QuantConnect API for you. With the MCP server, the client and its LLM provider do the reasoning, and QuantConnect only provides the tools.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/mcp-server/key-concepts>

### Why should I use Mia instead of ChatGPT or another general LLM to write QuantConnect code?

Mia knows your project context, the QuantConnect documentation, runtime errors, and logs. We trained Mia on hundreds of algorithms and thousands of documentation pages. Our benchmarks show Mia generates working QuantConnect code far more often than general commercial LLMs. Mia also compiles and backtests its own code, so it fixes errors before it hands the result back.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/predefined-agents/mia>

### How do I take a new trading idea to paper trading with an agent?

In the Algorithm Lab, open the Research Pipeline tab, add a card, and describe your trading idea. Select Mia in the Agent field, then click Deploy Mia. When the research finishes, prompt Mia to backtest the strategy, and when the backtest completes, ask Mia to deploy it to paper trading.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/getting-started>

## Agents

### What is a QuantConnect Agent?

An Agent is an AI specialist that handles one stage of building a trading strategy, such as idea sourcing, validation, backtesting, or paper trading. Agents are agentic: they take a goal, call a tool, observe the result, and decide the next step until the goal is met. The quality of an agent depends on its system prompt, its task, and the tools you let it use.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/agents>

### What does the Agent Harness do that my own agent loop doesn't?

The Agent Harness runs the model server-side next to the data, the Object Store, and a research node. It keeps runs alive for hours, saves state so a redeploy resumes where it stopped, and wakes the agent when a backtest or optimization finishes. It also coordinates multiple agents, enforces per-agent permissions, and recovers from provider failures without losing work.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/agents#02-Why-Use-Agent-Harness>

### How does an agent know what went wrong in a backtest?

Every agent backtest runs through the LEAN results analyzer, which hands the agent structured findings with concrete fixes. The analyzer checks for fatal errors, strategy weaknesses, execution problems, slow performance, and order errors. It runs during the backtest and again at completion, so the agent can stop a bad run early.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/agents#02-Why-Use-Agent-Harness>

### Can an agent trade real money or edit my files without my approval?

An agent can only deploy to live trading if you grant it the live trading write permission. Each agent has separate read and write permissions for files, backtests, optimizations, research, live trading, storage, data, the Kanban board, and notifications. In chat mode, file and notebook edits require your approval, with a diff preview before you approve.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/agents#02-Why-Use-Agent-Harness>

### What is the difference between the system prompt and the task prompt?

The system prompt is the standing brief you set when you create the agent, and the agent reads it on every deployment. The task prompt is set on each task and describes the specific work that task does. Put the agent's role and method in the system prompt and put task-specific inputs and constraints in the task prompt.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/agents#03-Create-System-Prompts>

### How do I write one system prompt that works for both Python and C# projects?

Wrap the language-specific instructions in `<python>` and `<csharp>` tags. The agent only sees the `<python>` content on a Python project and only sees the `<csharp>` content on a C# project.

```
<csharp> C# prompt details </csharp>
<python> Python prompt details </python>
```

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/agents#03-Create-System-Prompts>

### Why does my agent get slower or less accurate as I add more instructions or tools?

Everything the model sees counts against its context budget, and long contexts cost more, run slower, and crowd out reasoning. The model also considers every enabled tool at every step, so extra tools make it more likely to pick the wrong one. Keep the system prompt focused, grant only the permissions the agent needs, and name the tool to use at each step.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/agents#05-Select-Tools>

### Which LLM do Agents use, and is it free?

By default, Agents run on the free QuantConnect Meta Agent, which requires no setup. Organizations with a paid Agent Node can supply their own LLM provider API key. With your own key, you select the model, reasoning effort, maximum turns, and tool choice policy.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/agents#06-LLM-Providers>

### How do I make an agent return machine-readable results?

Define a JSON schema in the Output Schema field of the agent configuration. The agent then returns a response that conforms to the schema instead of free-form prose. Use a structured output when another agent, an API handler, or a person scanning for a key value consumes the result. Keep the schema as flat as possible.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/agents#07-Structured-Outputs>

### How do I make an agent start automatically when a card reaches a Research Pipeline stage?

In the agent configuration, assign the agent to a Research Pipeline stage. When a card lands in that stage, the assigned agent deploys and starts working on the project. Each agent can have one stage, and the predefined agents come with their stage already set.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/agents#08-Research-Pipeline>

### How do I teach agents my own rules, like risk limits or coding conventions?

Add a custom skill to the `.agent/skills/` folder of your organization's Object Store, with one folder and one `skill.md` file per skill. The `skill.md` file starts with YAML frontmatter that has a name and a description, followed by Markdown instructions. Agents load a skill only when its description matches the task, and every agent in the organization can use it.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/agents#14-Custom-Skills>

### How do I make agents remember facts about my project across conversations?

Write the facts to the `.assistant/memories/<project_id>/memory.md` file in your organization's Object Store, as a plain Markdown list or short notes. Agents that use memory load the file at the start of each run, and manual edits take effect on the next run. Agents also add new facts to the file as they learn them.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/agents#15-Custom-Memories>

### Can agents start new projects from my own template?

Yes. Put each template's project files in a subfolder of `.agent/templates/python/` or `.agent/templates/csharp/` in your organization's Object Store, and register it in that folder's `templates.json` file. Each entry has a name, folder, description, and tags array, and the folder must match the subfolder name. Write asset class tags in PascalCase, such as `Equity`, `Future`, `Option`, or `Crypto`.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/agents#16-Custom-Templates>

## Tasks

### What is the difference between an agent, a task, and a deployment?

An agent is the AI specialist and its configuration. A task is a unit of work you assign to an agent, defining what it does and when it runs. A deployment is one execution of a task, so a task can have many deployments over its lifetime.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/tasks>

### Does every agent task need a QuantConnect project?

No. Agents that write algorithm code or run research notebooks need a project, but a task like a news summarizer can run with no project. Each task can reuse one project it creates, create a fresh project on each deployment, use an existing project, or run with no project.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/tasks#03-Project-Preferences>

### Can I schedule an agent task to run automatically?

Organizations on the Trading Firm or Institution tier can schedule tasks with the same Scheduled Event system that QuantConnect algorithms use. Schedules include market open, fifteen minutes before close, and the first trading day of the month. Because these schedules follow market hours, a task can skip weekends and US market holidays. On other tiers, you deploy tasks manually with Deploy Now.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/tasks#04-Schedule-Work>

### How do I get notified when an agent task finishes or fails?

Set up email, SMS, or Telegram notifications on the Task Configuration page. For Telegram, create a group, add a bot such as `@quantconnect_notifications_bot`, and enter your user Id or group Id. The group Id is in the URL when you open the group chat in Telegram on the web.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/tasks#05-Notifications>

### What is the difference between chained agents and callable agents?

Chained agents run in a fixed order on every deployment, and each agent's output becomes the next agent's input. Callable agents act like tools that the assigned agent invokes only when it decides it needs them. Use a chain when the stages are known in advance, and use callable agents when the path depends on what the agent finds. Configure both in the Sub-Agent Orchestration section of the assigned agent.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/tasks#07-Callable-Agents>

## Deployments

### Where can I see what an agent did during a deployment?

Open the task from Organization > Agents > Tasks, and then click a deployment in the list. The deployment page shows the full conversation, including the agent's reasoning, tool calls, your replies, and its output. Click the project link at the top of the page to open the project the deployment worked on.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/deployments#03-View-Deployments>

### How do I remove a stuck or unwanted agent deployment?

Open the task from Organization > Agents > Tasks, click Delete in the row of the deployment, and then click Delete again to confirm. To stop an agent from your own tooling, use the REST API, which can also prompt an agent and check its status.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/deployments#04-Delete-Deployments>

### What is an agent node, and how many agents can I run at once?

Agent nodes are the servers that run your agent tasks and connect to the remote MCP server. The number of agent nodes and the node model set how many agent tasks you can run concurrently. For example, an A4-8 node runs four agents and an A16-32 node runs sixteen. See the Pricing page for the price of each model.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/deployments#05-Quotas>

### How many tokens does the free agent node include?

Your first organization includes one free A-MICRO agent node, capped at 100,000 tokens per month. The cap is lifted when you upgrade to a paid tier and add a new agent node. Paid agent nodes have no monthly cap, but a fair-use token allowance applies over a weekly rolling window.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/deployments#05-Quotas>

### What do I do when my agents reach their usage limit?

The fair-use allowance on paid agent nodes is measured over a weekly rolling window, so usage frees up as the window moves forward. To keep working sooner, upgrade to the next agent node model or bring your own LLM API key.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/deployments#05-Quotas>

### Can I choose which node an agent task uses?

Yes. By default, an agent task uses the best-performing resource, but you can select a specific resource in the Resources panel of the IDE. The Resources panel also shows the status of all your organization's nodes.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/deployments#05-Quotas>

## Agent Teams

### Why use a team of agents instead of one agent for everything?

Splitting the work keeps each agent's context small, because each specialist carries only what its stage needs. A specialist can also be tuned harder for one job than a generalist. Specialists run in parallel across projects, so one can research while another backtests and a third monitors live trading.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/agent-teams#02-Why-Work-in-Teams>

### What is the difference between an Agent Chain and an Agent Mesh?

An Agent Chain is a linear pipeline where work moves through specialists in a fixed order. An Agent Mesh, also called a Cluster, has a hub agent, usually the Conductor, that calls specialists as tools based on what the request needs. Use a chain for workflows with a natural sequence and a mesh for work whose path isn't known in advance.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/agent-teams#04-Agent-Chains-and-Mesh>

### How do I see what the sub-agents in a team said to each other?

Each conversation between agents lives in its own thread. On the deployment page, the main view shows the conversation with the Conductor. To view a sub-agent's conversation, click that agent at the top of the page.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/agent-teams#05-How-the-Team-Communicates>

## Predefined Agents

### What does the Conductor do?

The Conductor is a project manager agent that moves an idea through research, validation, backtesting, and paper trading by routing work to six specialist agents. If you bring no idea, it pulls the next card from your Research Pipeline or generates a new idea from recent news and trading blogs. It checks existing pipeline cards first to avoid duplicate projects.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/predefined-agents/conductor>

### What are the predefined specialist agents?

The predefined specialists are the Ideas Agent, Research Agent, Research Validation Agent, Backtest Agent, Paper Testing Agent, and Live Monitoring Agent. Each one covers one stage, from sourcing ideas to watching live positions. The Conductor coordinates them, and Mia is a generalist that can use every tool.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/predefined-agents/conductor>

### What does the Research Agent produce, and does it run a backtest?

The Research Agent produces a research notebook that documents the data, transformations, variable selection, candidate models, and residual tests behind an idea. It doesn't run a backtest. Its notebook tells you whether the idea is worth coding before you spend time on an algorithm.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/predefined-agents/research-agent>

### Why doesn't the Backtest Agent summarize my backtest results?

The Backtest Agent leaves the summary to the backtest report, which describes the results better than prose. It writes the algorithm, fixes compile and runtime errors, and repeats until the strategy runs end to end. It never calls the code production-ready, because paper trading is the next test.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/predefined-agents/backtest-agent>

### Why did the Paper Testing Agent stop my paper trading algorithm?

The Paper Testing Agent stops an algorithm that behaves differently from its code or backtest. It also stops one whose performance leaves the backtest's statistical envelope. It first tries to diagnose and redeploy, up to three attempts. When it stops, it reports which check failed, what it observed and expected, what it tried, and its best hypothesis.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/predefined-agents/paper-testing-agent>

### Why didn't I get an email from the Live Monitoring Agent?

The Live Monitoring Agent only sends an email when it finds a material adverse event for one of your live holdings. It only counts news that cuts against the position's direction and filters out routine items like earnings that met expectations. If nothing material turns up, it sends no email. It never recommends a trade.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/predefined-agents/live-monitoring-agent>

### Where do I chat with Mia in the Cloud IDE?

Open a project and, in the right navigation menu, click the Ask Mia icon. Mia has your project context, so she can edit project files, run backtests, and deploy your algorithm.

Docs: <https://www.quantconnect.com/docs/v2/cloud-platform/projects/ide#07-AI-Chats>

### Can Mia work in a research notebook?

Yes. When research is the right next step, Mia opens the project's research notebook and works inside it with the notebook tools. When you ask for research, you get a notebook that documents the data, transformations, and tests behind her conclusion.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/predefined-agents/mia>

### Why does Mia let my algorithm fail instead of catching the error?

Mia doesn't wrap runtime errors in try/catch blocks to hide them. She lets the algorithm fail to find and fix the root cause. Then she compiles and backtests again until the strategy runs end to end.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/predefined-agents/mia>

### Can I ask Mia questions outside the IDE?

Yes. Mia posts an initial response to discussions in the community forum. In the QuantConnect Discord server, mention @Mia Alissi or use the #ask-mia channel. In these channels, Mia replies with text only.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/predefined-agents/mia#11-Public-Support>

## Bring Your Own Key

### Who can bring their own LLM API key?

Organizations with a paid Agent Node can supply their own API key and run Agents on the LLM provider they choose. Without your own key, Agents run on the free QuantConnect Meta Agent. Bring your own key to run a specific model, tune its reasoning, or route the cost through your provider account.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/bring-your-own-key>

### Which LLM providers can I use with my own key?

The docs give key setup steps for Anthropic, DeepSeek, Google, Groq, and OpenAI. Create the key in the provider's console, then paste it into the Token field of a secret in your organization.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/bring-your-own-key>

### What settings can I change when I use my own provider?

With your own provider, you can set the reasoning effort, the maximum turns per deployment, and the tool choice policy. The defaults are high reasoning effort, 100 maximum turns, and an `auto` tool choice that lets the model decide when to call tools.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/bring-your-own-key>

### How do I store my LLM API key for agents?

Log in to the Algorithm Lab, click Organization > Secrets, and then click New Secret. Select Llm Provider Token as the key, choose the provider, paste the token, select the model, and click Save. The token is write-only, so the Secrets page only shows a masked preview.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/bring-your-own-key#02-Secrets>

### How do I change the model of a secret without re-entering my API key?

On the Secrets page, click Update on the secret and leave the Token field blank to keep the current token. Because the token and its configuration save together, re-enter the token if you change any configuration below the Token field.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/bring-your-own-key#02-Secrets>

### Can I switch the API key of a task that is already deployed?

No. You can't change the secret after you deploy a task, so start a new task to use a different secret. When you run an Agent Team, the orchestrator's secret applies to the whole team.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/bring-your-own-key#02-Secrets>

## MCP Server

### What is the QuantConnect MCP server?

The QuantConnect Model Context Protocol (MCP) server connects an LLM client to the QuantConnect API. It gives the LLM tools to create projects, edit files, run backtests and optimizations, work in research notebooks, and deploy live algorithms. You prompt the LLM in plain language and it calls the API for you.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/mcp-server/key-concepts>

### What is the URL of the QuantConnect MCP server?

The remote MCP server URL is `https://www.quantconnect.com/api/v2/mcp`. Add it to your MCP client's configuration and then, in the Authorize MCP Server form, select the organization to connect and click Authorize.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/mcp-server/claude-code>

### Why does the MCP server say it requires a dedicated Assistant node?

The remote MCP server requires a paid agent node, such as an A1-1, A4-8, or A16-32 node. If your organization only has the free A-MICRO agent node, the server returns this error. To resolve it, add a paid agent node to your organization.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/mcp-server/key-concepts#08-Troubleshooting>

### Which AI clients work with the QuantConnect MCP server?

The docs have setup guides for Claude Code, GitHub Copilot in Local Platform, Cursor, and Devin Desktop. Other MCP clients can also connect, and the MCP website lists them in its feature support matrix. Clients that run in the terminal can edit files on your local computer.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/mcp-server>

### How do I connect Copilot in Local Platform to the MCP server?

Install the GitHub Copilot Chat extension, run MCP: Open User Configuration from the Command Palette, and add the server to `mcp.json`. Save the file, click Allow, and authorize your organization in the browser that is logged in to QuantConnect. Then open View > Chat and switch the chat to Agent mode.

```json
{
  "servers": {
    "qc-mcp": {
      "url": "https://www.quantconnect.com/api/v2/mcp",
      "type": "http"
    }
  }
}
```

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/mcp-server/copilot>

### How do I connect Cursor to the MCP server?

Install the QuantConnect extension in Cursor, log in, and pull your organization workspace. Then open Customize from the Command Palette, click MCPs > New MCP Server > User, and add the server to `~/.cursor/mcp.json`. When the agent asks you to authenticate your QuantConnect account, click Authenticate.

```json
{
  "mcpServers": {
    "qc-mcp": {
      "url": "https://www.quantconnect.com/api/v2/mcp",
      "transport": {
        "type": "Streamable HTTP"
      }
    }
  }
}
```

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/mcp-server/cursor>

### Why does the Claude Code authorization page open in the wrong browser?

The Connect step opens the authorization page in your default browser. If you are signed in to QuantConnect in a different browser, change your computer's default browser and repeat the steps. In the Authorize MCP Server form, select the organization to connect and click Authorize.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/mcp-server/claude-code>

### How do I test that my MCP client is connected?

Enter the prompt "Read the open project." in the client's chat. If the connection works, the agent calls the `read_open_project` tool. In the Claude Code panel of Local Platform, you can also enter `/mcp` to see whether the QuantConnect MCP Server is connected.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/mcp-server/key-concepts#99-Examples>

### Does the QuantConnect MCP server have usage quotas?

The QuantConnect API has no quotas, but your MCP client and its LLM provider have their own. For example, Claude, Copilot, Cursor, and Devin Desktop each publish their own plan limits.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/mcp-server/key-concepts#07-Quotas>

### Why shouldn't my MCP client poll for backtest status?

The `create_backtest` and `create_optimization` tools queue the job and return an Id right away. When the job finishes, the server delivers the results to the client as a new message. Calling `read_backtest` or `read_optimization` before then returns empty or stale data.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/mcp-server/key-concepts#04-Tools>

### What can the MCP server do with live algorithms?

The `create_live_algorithm` tool deploys to paper trading with the QuantConnect brokerage. The live tools can also read status and holdings, search logs, stop an algorithm without liquidating, or liquidate and stop.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/mcp-server/key-concepts#04-Tools>

### How do I tailor how Claude Code behaves with my QuantConnect projects?

Write your conventions in a `CLAUDE.md` file. Claude Code loads it at the start of each conversation from `~/.claude/` and from the current, parent, and child directories of where you run `claude`. The file has no strict format, so you can list coding style, data, risk management, and communication rules.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/mcp-server/claude-code#04-Tailor-Behavior>

### Why does Devin Desktop fail on long backtests through the MCP server?

Devin Local truncates large responses from MCP servers, so it can be unstable for long backtests.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/mcp-server/devin-desktop>

### Do I need Docker to use the MCP server with Cursor or Devin Desktop?

You only need Docker Desktop to backtest and live trade with the local Deployment Target. The remote MCP server itself runs at `https://www.quantconnect.com/api/v2/mcp`.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/mcp-server/cursor>

### What should I check when the MCP server stops responding?

The MCP server relies on the QuantConnect API, your client application, and the LLM. Check the QuantConnect Status page, then the status page of your client and of your LLM provider, such as the Anthropic Status page for Claude.

Docs: <https://www.quantconnect.com/docs/v2/ai-assistance/mcp-server/key-concepts#08-Troubleshooting>
