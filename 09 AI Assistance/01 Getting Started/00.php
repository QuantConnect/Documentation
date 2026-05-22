<style>
    .docs-tutorial .tutorial-header {
        text-align: center;
        background: url(https://cdn.quantconnect.com/i/tu/tut-header-dots-top-left.svg), url(https://cdn.quantconnect.com/i/tu/tut-header-dots-bottom-right.svg), url(https://cdn.quantconnect.com/i/tu/tut-header-stripes-bottom-left.svg), url(https://cdn.quantconnect.com/i/tu/tut-header-stripes-top-right.svg);
        background-position: top 10px left 10px, bottom 10px right 10px, bottom left, top right;
        background-repeat: no-repeat;
        background-color: #F3F5F8;
        border-radius: 4px;
        padding-top: 2rem;
    }

    .docs-tutorial .tutorial-header h3 {
        padding: 2.5rem 1rem 1rem 1rem;
        text-align: center;
    }

    .docs-tutorial .tutorial-header p {
        padding: 0 1rem 1rem 1rem;
        text-align: center;
    }

    .docs-tutorial .tutorial-step {
        position: relative;
        border: 1px solid #D9E1EB;
        border-radius: 4px;
        margin-bottom: 1rem;
    }

    .docs-tutorial .tutorial-step p {
        padding: 0.5rem 1rem;
        margin-bottom: 0;
    }

    .docs-tutorial .tutorial-step .circle-icon {
        margin: 0 0.2rem;
    }

    .docs-tutorial .bottom-screenshot {
        text-align: center;
        padding: 0 1rem;
        margin-top:2rem;
    }

    .docs-tutorial .bottom-screenshot img {
        vertical-align: bottom;
        width: 100%;
        max-width: 700px;
    }
</style>

<div class="docs-tutorial">
    <div class="tutorial-header">
        <h3 style="text-align:center;">QUANTCONNECT ASSISTANCE</h3>
        <p style="max-width:70ch;margin: 0 auto;">Guide through creating a project, running your first backtest,
            and live algo trading in QuantConnect Local Platform.</p>
        <div class="bottom-screenshot"><img src="https://cdn.quantconnect.com/i/tu/local-platform-header.webp"
                alt="Local Platform" />
        </div>
    </div>

    <p style="margin-top: 2rem;">
      We are deeply integrating AI into our core product to make designing algorithms on QuantConnect as easy as possible. 
      Most clients will experience the benefits of this without realizing it, as our platform quietly solves issues. 
      If you'd prefer a more hands-on approach or a local LLM integration, you can harness some of the new tools we publish to make this easier—such as our chatbots, agents, and an MCP server.
    </p>

    <h4>QuantConnect Chatbot</h4>
    <p>
      Mia is our <span class='new-term'>agentic</span> coding assistant. 
      Think of agentic coding as a form of pair programming, where you are the observer who gives high-level directions and Mia is the driver who writes the code and calls the QuantConnect API. 
      She is aware of the entire project's context, QuantConnect documentation, runtime errors, and logs, and uses a proprietary blend of commercial and open-source models to accomplish the goals you set. 
      We trained Mia on hundreds of algorithms and thousands of documentation pages to provide contextual assistance for most issues you may encounter when developing a strategy. 
      Agentic conversations like this unlock the ability for everyone to create, research, and deploy algorithmic trading strategies without being an expert programmer. 
    </p>

    <p>
      We encourage using Mia before other commercially available LLMs. 
      Our benchmarks show Mia is able to generate working QuantConnect code in 75% of test cases vs OpenAI o3 25%. 
    </p>

    <h4>QuantConnect MCP Server</h4>
    <p>
    The QuantConnect MCP server is a bridge for AIs to interact with our cloud platform. This server equips the LLM with tools to create projects, run backtests, deploy live algorithms, and more. For more information, see <a href="/docs/v2/ai-assistance/mcp-server">MCP Server</a>. 
    </p>

    <h4>QuantConnect Assistants</h4>
    <p><a href="/docs/v2/ai-assistance/assistants">Assistants</a> are specialized AI agents that handle the distinct stages of building a trading strategy. They source ideas, validate them, code the backtest, and shepherd the result through paper trading. Each one is an expert in a single part of the workflow, with the tools to do the work rather than just describe it. Talk to an assistant directly, or hand the whole job to the Conductor and let it route the work through the team on your behalf.</p>
    <p>To supercharge your productivity, add assistants to your <a href="/docs/v2/cloud-platform/research-pipeline">Research Pipeline</a>, a kanban board that tracks your projects through the stages of research validation, backtesting, paper trading, and live trading.</p>


    <hr />
    <h4>Follow these steps to create and deploy a new strategy to paper trading with the help of the Assistants:</h4>
    <div class="tutorial-step">
        <p>1. <a href="/terminal/">Log in to the Algorithm Lab</a>.</p>
    </div>
    <div class="tutorial-step">
        <p>2. Click the <span class='tab-name'>Research Pipeline</span> tab.</p>
    </div>
    <div class="tutorial-step">
        <p>3. On the Research Pipeline, click the <img class="inline-icon" src="https://cdn.quantconnect.com/i/tu/research-pipeline-plus-icon.png"> <span class="icon-name">Add card</span> icon.</p>
    </div>
    <div class="tutorial-step">
        <p>4. In the description text area, describe a new trading idea you want to investigate.</p>
        <blockquote><p>> Research this strategy idea: SMA crossover trading algorithm on SPY. When it's below the SMA, hold gold instead.</p></blockquote>
    </div>
    <div class="tutorial-step">
        <p>5. In the bottom-left corner, click the <span class='field-name'>Assistant</span> field and then click <span class='field-name'>Mia</span> from the drop-down menu.</p>
    </div>
    <div class="tutorial-step">
        <p>6. Click <span class='button-name'>Deploy Mia</span>.</p>
        <p>Congratulations! You've just deployed your first assistant. Mia will read your description and then get to work researching the idea, and writing code.</p>
    </div>
    <div class="tutorial-step">
        <p>7. When Mia finishes the research, prompt her to backtest the strategy.</p>
        <blockquote><p>> Backtest this strategy.</p></blockquote>
    </div>
    <div class="tutorial-step">
        <p>8. When the backtest completes, ask Mia to deploy the strategy to paper trading.</p>
        <blockquote><p>> Deploy this strategy to paper trading.</p></blockquote>
    </div>
    <div class="tutorial-step">
        <p>9. At the top of the conversation, click the project name to open the project.</p>
    </div>
    <div class="tutorial-step">
        <p>10. At the top of the project page, click the <img class="inline-icon" src= "https://cdn.quantconnect.com/i/tu/deploy-live-icon.png" alt="Deploy live icon"> <span class="icon-name">Deploy Live</span> icon.</p>
        <p>This page shows you the live performance of your strategy as it runs in the market, and you can check back here anytime to see how it's doing.</p>
    </div>
    
</div>
