<p>To connect the Agent in the Cursor IDE to the QC MCP Server, follow these steps:</p>

<ol>  
  <!-- Install Cursor -->
  <li>Install and open <a href='https://cursor.com/downloads' rel='nofollow' target='_blank'>Cursor</a>.</li>

  <!-- Install the QC Extension -->
  <li>In the top navigation bar, click <span class='menu-name'>View > Extensions</span>.</li>
  <li>In the <span class='field-name'>Search Exensions in Marketplace</span> field, enter <span class='key-combinations'>QuantConnect</span>.</li>
  <li>Click <span class='button-name'>Install</span>.</li>

  <!-- Log in to QC -->
  <li>In the primary side bar, click the <img src='https://cdn.quantconnect.com/i/tu/cursor-qc-icon.png' class='inline-icon' alt='QuantConnect icon in Cursor'> <span class='icon-name'>QuantConnect</span> icon.</li>
  <li>The primary side bar checks the following requirements on your local machine. If any of the checks fail, see the related documentation.</li>
    <ul>
        <li><a href='/docs/v2/lean-cli/installation/installing-lean-cli'>LEAN CLI is installed</a>.</li>
        <li>You are logged in to QuantConnect.</li>
    </ul>
  <li>In the Initialization Checklist panel, click <span class="button-name">Login to QuantConnect</span>.</li>
  <img src='https://cdn.quantconnect.com/i/tu/cursor-initialization-checklist.png' class='docs-image' alt='Initialization checklist in Cursor, where you can log in to QuantConnect'>
  <li>In the Cursor window, click <span class="button-name">Open</span>.</li>
  <img src='https://cdn.quantconnect.com/i/tu/cursor-login-to-quantconnect.png' class='docs-image' alt='Pop-up that enables you to open a QC link to login to your account'>
  <li>On the Code Extension Login page, click <span class="button-name">Grant Access</span>.</li>

  <!-- Pull organization workspace -->
  <li>In the Select Workspace panel, click <span class="button-name">Pull Organization Workspace</span>.</li>
  <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/select-workspace.png" alt="Select workspace">
  <li>In the Pull QuantConnect Organization Workspace window, click the cloud workspace (<a href='https://www.quantconnect.com/docs/v2/cloud-platform/organizations'>organization</a>) that you want to pull.</li>
  <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/pull-cloud-workspaces-1.png" alt="Pull cloud organization">  
  <li>In the Pull QuantConnect Organization Workspace window, create a directory to serve as the organization workspace and then click <span class="button-name">Select</span>.</li>
  <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/pull-cloud-workspaces-2.png" alt="Pull cloud workspace">
  <p>It takes a few minutes to create a new organization workspace directory and populate it with the <a href='/docs/v2/local-platform/development-environment/organization-workspaces#07-Directory-Structure'>the initial file structure</a>. After the organization workspace is populated with the initial file structure, it pulls your cloud project files.</p>
  
  <!-- Configure the MCP Server -->
  <li>In your <span class='public-file-name'>~ / .cursor / mcp.json</span> file, add the following configuration:</li>
   <div class="section-example-container">
     <pre>{
  "mcpServers": {
    "qc-mcp": {
      "url": "http://localhost:3001/",
      "transport": {
        "type": "Streamable HTTP"
      }
    }
  }
}</pre>
   </div>

  <!-- Open a QC project -->
  <li>In Cursor, <a href='/docs/v2/local-platform/projects/getting-started#03-Create-Projects'>create a new project</a> or <a href='/docs/v2/local-platform/projects/getting-started#04-Open-Projects'>open an existing one</a>.</li>
  
  <!-- Ensure the tools are discovered -->
  <li>In the top navigation bar, click <span class='menu-name'>File > Preferences > Cursor Settings</span>.</li>
  <li>On the Cursor Settings page, click <span class='menu-name'>Tools & MCP</span>.</li>
  <li>If the QC MCP Server has an error, click the toggle switch twice to restart it.</li>
  <img src='https://cdn.quantconnect.com/i/tu/cursor-mcp-error.png' alt='Cursor GUI showing the qc-mcp server has an error' class='docs-image'>

  <!-- Open the chat window -->
  <li>Press <span class='key-combinations'>Ctrl+Alt+b</span> to chat with the agent.</li>
</ol>
