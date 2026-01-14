<p>To connect the Agent in the Cursor IDE to the QC MCP Server, follow these steps:</p>

<ol>  
  <!-- Install Cursor -->
  <li>Install and open <a href='https://cursor.com/downloads' rel='nofollow' target='_blank'>Cursor</a>.</li>

  <!-- Install the QC Extension -->
  <li>In the top navigation bar, click <span class='menu-name'>View > Extensions</span>.</li>
  <li>In the <span class='field-name'>Search Exensions in Marketplace</span> field, enter <span class='key-combinations'>QuantConnect</span>.</li>
  <li>Click <span class='button-name'>Install</span>.</li>

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
  <li>Click <span class='button-name'>New MCP Server</span>.</li>
  <li>If the QC MCP Server has an error, click the toggle switch twice to restart it.</li>
  <img src='https://cdn.quantconnect.com/i/tu/cursor-mcp-error.png' alt='Cursor GUI showing the qc-mcp server has an error' class='docs-image'>

  <!-- Open the chat window -->
  <li>Press <span class='key-combinations'>Ctrl+Alt+b</span> to chat with the agent.</li>
</ol>
