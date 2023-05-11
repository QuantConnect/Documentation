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
            <h3 style="text-align:center;">QUANTCONNECT LOCAL PLATFORM</h3>
            <p style="max-width:70ch;margin: 0 auto;">Guide through creating a project, running your first backtest,
                and live algo trading in QuantConnect Local Platform.</p>
            <div class="bottom-screenshot"><img src="https://cdn.quantconnect.com/i/tu/local-platform-header.webp"
                    alt="Local Platform" />
            </div>
        </div>
        <p style="margin-top: 2rem;">The Local Platform enables you to seamlessly develop quant strategies on-premise and in QuantConnect Cloud,
            getting the best of both environments. With Local Platform, you can harness your local version control,
            autocomplete, and coding tools with the full power of a scalable cloud at your finger tips. We intend to
            keep complete feature parity with our cloud environment, allowing you to harness cloud or local datasets to
            power on-premise quantitative research.</p>

        <p>We encourage a hybrid “cloud + local” workflow, so you can use right tool for each stage of your development
            process. With the Local Platform, you can create, debug, and run projects on premise while using your own
            on-site tools. With the Cloud Platform you can deploy backtests at scale and harness our massive data
            library at low cost.</p>
        <hr />
        <h4>Follow these steps to create a new trading algorithm and backtest it in QuantConnect Cloud:</h4>
        <div class="tutorial-step">
            <p>1. <a href="/docs/v2/local-platform/installation">Install Local Platform</a>.</p>
        </div>
        <div class="tutorial-step">
            <p>2. Open<img src="https://cdn.quantconnect.com/i/tu/vscode-inline-icon.svg"
                alt="VSCode" class="inline-icon circle-icon" />Visual Studio Code</a>.</p>
        </div>
        <div class="tutorial-step">
            <p>3. In the Initialization Checklist panel, click <span class="button-name">Login to QuantConnect</span>.</p>
            <div class="bottom-screenshot">
                <img src="https://cdn.quantconnect.com/i/tu/local-platform-login.webp" alt="Local Platform Login" />
            </div>
        </div>
        <div class="tutorial-step">
            <p>4. In the Visual Studio Code window, click <span class="button-name">Open</span>.</p>
            <div class="bottom-screenshot">
                <img src="https://cdn.quantconnect.com/i/tu/local-platform-open-website.webp" alt="Open Website" />
            </div>
        </div>
        <div class="tutorial-step">
            <p>5. On the Code Extension Login page, click <span class="button-name">Grant Access</span>.</p>
        </div>
        <div class="tutorial-step">
            <p>6. In VS Code, in the Select Workspace panel, click <span class="button-name">Pull Organization Workspace</span>.</p>
            <div class="bottom-screenshot">
                <img src="https://cdn.quantconnect.com/i/tu/local-platform-pull-org.webp" alt="Pull Organization" />
            </div>
        </div>
        <div class="tutorial-step">
            <p>7. In the Pull QuantConnect Organization Workspace window, click the cloud workspace (<a href="/docs/v2/cloud-platform/organizations">organization</a>) that 
                you want to pull.</p>
            <div class="bottom-screenshot">
                <img src="https://cdn.quantconnect.com/i/tu/local-platform-choose-org.webp" alt="Choose Organization" />
            </div>
        </div>
        <div class="tutorial-step">
            <p>8. In the Pull QuantConnect Organization Workspace window, create a directory to serve as the organization 
                workspace and then click <span class="button-name">Select</span>.</p>
            <p>If you are running Docker on Windows using the legacy Hyper-V backend instead of the new WSL 2 backend, you need to enable file sharing for your temporary directories and for your organization workspace. To do so, open your Docker settings, go to <span class="button-name">Resources > File Sharing</span> and add <span class="button-name">C: / Users / <username> / AppData / Local / Temp</span> and your organization workspace path to the list. Click <span class="button-name">Apply & Restart</span> after making the required changes. </p>
            <div class="bottom-screenshot">
                <img src="https://cdn.quantconnect.com/i/tu/local-platform-create-dir.webp" alt="Create Directory" />
            </div>
        </div>
        <div class="tutorial-step">
            <p>9. In the Open Project panel, click <span class="button-name">Create Project</span>.</p>
            <div class="bottom-screenshot">
                <img src="https://cdn.quantconnect.com/i/tu/local-platform-create-project.webp" alt="Create Project">
            </div>
        </div>
        <div class="tutorial-step">
            <p>10. Enter the project name and then press <span class="button-name">Enter</span>.</p>
            <p>Congratulations! You just created your first local project.</p>
            <div class="bottom-screenshot">
                <img src="https://cdn.quantconnect.com/i/tu/local-platform-project-created.webp" alt="Project Created">
            </div>
        </div>
        <div class="tutorial-step">
            <p>11. In the top-right corner of VS Code, click<img src="https://cdn.quantconnect.com/i/tu/tut-build-icon.svg" alt="Gear Icon" class="inline-icon circle-icon" /><span class="button-name">Build</span> and then click<img src="https://cdn.quantconnect.com/i/tu/tut-backtest-icon.svg" alt="Backtest Icon" class="inline-icon circle-icon" /><span class="button-name">Backtest</span>.</p>
            <p>The backtest results page displays your algorithm’s performance over the backtest period.</p>
            <div class="bottom-screenshot">
                <img src="https://cdn.quantconnect.com/i/tu/local-platform-backtest-proj.webp" alt="Backtest Project">
            </div>
        </div>
    </div>