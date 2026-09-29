<p>The following example demonstrates checking the ticket quota of your support seat, listing the support tickets of your organization, reading the messages of an open ticket, and opening a new ticket with an attached project, backtest, live deployment, and AI conversation through the cloud API.</p>

<div class="section-example-container">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

### Check Ticket Quota
# Send a POST request to the /organizations/support/tickets/list endpoint to get the ticket quota and the tickets
response = post(f'{BASE_URL}/organizations/support/tickets/list', headers=get_headers(), json={
    "organizationId": ORGANIZATION_ID,
    "closedPage": 0  # Page of 10 closed tickets, starting at 0
})
# Parse the JSON response into python managable dict
result = response.json()
# Print the number of new tickets you can open. Every ticket you opened in the last month counts, even if it's closed
remaining = result['remaining'] if result['success'] else 0
print(f"Remaining tickets: {remaining} of {result.get('allowance')}")

### List Support Tickets
# The same response contains the open tickets and the requested page of closed tickets
open_tickets = result.get('open', [])
for ticket in open_tickets:
    print(ticket['data']['external_id'], ticket['status'], ticket['title'])

### Read Support Ticket
if open_tickets:
    # Send a POST request to the /organizations/support/tickets/read endpoint to read the first open ticket
    response = post(f'{BASE_URL}/organizations/support/tickets/read', headers=get_headers(), json={
        "organizationId": ORGANIZATION_ID,
        "hash": open_tickets[0]['hash']
    })
    # Parse the JSON response into python managable dict
    result = response.json()
    # Check if the request was successful and print the messages
    if result['success']:
        for message in result['messages']:
            print(message['time'], message['name'], message['message'])

### Create Support Ticket (only when the quota has tickets left)
if remaining > 0:
    # Send a POST request to the /organizations/support/tickets/create endpoint to open a new ticket
    # Each new ticket uses one ticket from the quota of your support seat
    response = post(f'{BASE_URL}/organizations/support/tickets/create', headers=get_headers(), json={
        "organizationId": ORGANIZATION_ID,
        "subject": "Backtest orders don't fill at the expected price",
        "message": "&lt;p&gt;The market orders in the attached backtest fill at the previous close.&lt;/p&gt;",
        "projectId": 12345678,             # Optional. Gives the Support Team permission to view the project
        "backtestId": "1c991c7eec8a7bd3b4bfc7e18a376fa6",  # Optional. Backtest of the attached project
        "deployId": "L-141106d80de1da9a9f85ea07c06bf7b6",  # Optional. Stopped live deployment of the attached project
        "agentDeploymentId": "A-3306e3e787f321efadf5c4392861d68d"  # Optional. AI conversation of the attached project
    })
    # Parse the JSON response into python managable dict
    result = response.json()
    # Keep the hash of the new ticket to find it and comment on it
    ticket_hash = result['hash']

    ### Get the Id of the New Support Ticket
    # Send a POST request to the /organizations/support/tickets/list endpoint to find the new ticket
    response = post(f'{BASE_URL}/organizations/support/tickets/list', headers=get_headers(), json={
        "organizationId": ORGANIZATION_ID
    })
    # Parse the JSON response into python managable dict
    result = response.json()
    # Print the Id to mention when you contact our Support Team about the ticket
    ticket = next(t for t in result['open'] if t['hash'] == ticket_hash)
    print(f"Ticket Id: {ticket['data']['external_id']}")

    ### Comment on Support Ticket
    # Send a POST request to the /organizations/support/tickets/create endpoint with the hash to comment on the ticket
    response = post(f'{BASE_URL}/organizations/support/tickets/create', headers=get_headers(), json={
        "organizationId": ORGANIZATION_ID,
        "hash": ticket_hash,
        "message": "&lt;p&gt;The issue is resolved. Thank you.&lt;/p&gt;",
        "close": True  # Close the ticket after you add the comment
    })
    # Parse the JSON response into python managable dict
    result = response.json()
    print(result)</pre>
</div>
