<?php echo file_get_contents(DOCS_RESOURCES."/notifications/webhook-intro.html"); ?>

<p>Follow these steps to set up webhook notifications in the deployment wizard:</p>

<ol>
    <li>On the Deploy Live page, enable at least one of the notification types.</li>
    <p>The following table shows the supported notification types:</p>

    <table class="table qc-table">
        <thead>
            <tr>
                <th>Notification Type</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Order Events</td>
                <td>Notifications for when the algorithm receives <code>OrderEvent</code> objects</td>
            </tr>
            <tr>
                <td>Insights</td>
                <td>Notifications for when the algorithm emits <code>Insight</code> objects</td>
            </tr>
        </tbody>
    </table>
    <li>Click <span class="button-name">Webhook</span>.</li>
    <li>Enter a URL.</li>
    <li>If you want to add header information, click <span class="button-name">Add Header</span> and then enter a key and value.</li>
    <p>Repeat this step to add multiple header keys and values.</p>
    <li>Click <span class="button-name">Add</span>.</li>
    <p>To add more webhook notifications, click <span class="button-name">Add Notification</span> and then continue from step 2.</p>
</ol>

<h4>JSON Payload Schema</h4>
<p>The webhook HTTP-POST request sends a JSON payload with the following schema:</p>

<table class="table qc-table">
    <thead>
        <tr>
            <th style="width: 20%">Property</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>ProjectName</code></td>
            <td><code>string</code><br/>Name of the project.</td>
        </tr>
        <tr>
            <td><code>ProjectId</code></td>
            <td><code>integer</code><br/>Id of the project.</td>
        </tr>
        <tr>
            <td><code>Insights</code></td>
            <td><code>Insight Array</code><br/>Collection of insights emitted since the last notification. See <a href='/docs/v2/cloud-platform/api-reference/live-management/read-live-algorithm/insights#03-Responses'>Insight</a> for the schema of each object.</td>
        </tr>
        <tr>
            <td><code>OrderEvents</code></td>
            <td><code>OrderEvent Array</code><br/>Collection of order events since the last notification. See the Order Event Schema table below for the schema of each object.</td>
        </tr>
        <tr>
            <td><code>Portfolio</code></td>
            <td><code>Portfolio Array</code><br/>Current portfolio holdings. See the Portfolio Schema table below for the schema of each object.</td>
        </tr>
    </tbody>
</table>

<h4>Order Event Schema</h4>
<p>Each object in the <code>OrderEvents</code> array has the following properties:</p>

<table class="table qc-table">
    <thead>
        <tr>
            <th style="width: 20%">Property</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>OrderId</code></td>
            <td><code>integer</code><br/>Id of the order.</td>
        </tr>
        <tr>
            <td><code>Id</code></td>
            <td><code>integer</code><br/>Id of the order event.</td>
        </tr>
        <tr>
            <td><code>Symbol</code></td>
            <td><code>object</code><br/>Symbol information with <code>value</code> (ticker), <code>id</code> (symbol Id), and <code>permtick</code> (permanent ticker) properties.</td>
        </tr>
        <tr>
            <td><code>UtcTime</code></td>
            <td><code>string</code><br/>UTC time of the order event in ISO 8601 format.</td>
        </tr>
        <tr>
            <td><code>Status</code></td>
            <td><code>integer</code><br/>Order status. 1 = Submitted, 2 = PartiallyFilled, 3 = Filled, 5 = Canceled, 6 = Invalid, 7 = CancelPending, 8 = UpdateSubmitted.</td>
        </tr>
        <tr>
            <td><code>OrderFee</code></td>
            <td><code>object</code><br/>Order fee with a <code>Value</code> object containing <code>Amount</code> (number) and <code>Currency</code> (string) properties.</td>
        </tr>
        <tr>
            <td><code>FillPrice</code></td>
            <td><code>number</code><br/>Price at which the order was filled.</td>
        </tr>
        <tr>
            <td><code>FillPriceCurrency</code></td>
            <td><code>string</code><br/>Currency of the fill price.</td>
        </tr>
        <tr>
            <td><code>FillQuantity</code></td>
            <td><code>number</code><br/>Quantity filled in this event.</td>
        </tr>
        <tr>
            <td><code>Direction</code></td>
            <td><code>integer</code><br/>Order direction. 0 = Buy, 1 = Sell, 2 = Hold.</td>
        </tr>
        <tr>
            <td><code>IsAssignment</code></td>
            <td><code>boolean</code><br/>Whether the order is an option assignment.</td>
        </tr>
        <tr>
            <td><code>Quantity</code></td>
            <td><code>number</code><br/>Total quantity of the order.</td>
        </tr>
    </tbody>
</table>

<h4>Portfolio Schema</h4>
<p>Each object in the <code>Portfolio</code> array has the following properties:</p>

<table class="table qc-table">
    <thead>
        <tr>
            <th style="width: 20%">Property</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>Ticker</code></td>
            <td><code>string</code><br/>Ticker of the holding.</td>
        </tr>
        <tr>
            <td><code>Quantity</code></td>
            <td><code>number</code><br/>Quantity held.</td>
        </tr>
        <tr>
            <td><code>AveragePrice</code></td>
            <td><code>number</code><br/>Average price of the holding. This property is not present for cash holdings.</td>
        </tr>
        <tr>
            <td><code>UnreazliedProfit</code></td>
            <td><code>number</code><br/>Unrealized profit of the holding. This property is not present for cash holdings.</td>
        </tr>
    </tbody>
</table>

<h4>Example</h4>
<p>The following JSON shows an example webhook payload:</p>
<div class="cli section-example-container"><pre>
{
  "ProjectName": "My Algorithm",
  "ProjectId": 29064060,
  "Insights": [
    {
      "Id": "a0af97846797499dbd515753bdb73a0c",
      "GroupId": null,
      "SourceModel": "4a834e9d-f10f-4ebe-9fd9-df5fc509b0f8",
      "GeneratedTime": 1773767160.2239125,
      "CreatedTime": 1773767160.2239125,
      "CloseTime": 1777482360.2239125,
      "Symbol": "SPY R735QTJ8XC9X",
      "Ticker": "SPY",
      "Type": "price",
      "reference": 671.77,
      "ReferenceValueFinal": 0,
      "Direction": "up",
      "Period": 2592000,
      "Magnitude": null,
      "Confidence": null,
      "Weight": null,
      "ScoreIsFinal": false,
      "ScoreMagnitude": "0",
      "ScoreDirection": "0",
      "EstimatedValue": "0",
      "Tag": ""
    }
  ],
  "OrderEvents": [
    {
      "OrderId": 1,
      "Id": 1,
      "Symbol": {
        "value": "AAPL",
        "id": "AAPL R735QTJ8XC9X",
        "permtick": "AAPL"
      },
      "UtcTime": "2026-03-17T17:06:00.2239123Z",
      "Status": 3,
      "OrderFee": {
        "Value": {
          "Amount": 1,
          "Currency": "USD"
        }
      },
      "FillPrice": 254.05,
      "FillPriceCurrency": "USD",
      "FillQuantity": 130,
      "Direction": 0,
      "IsAssignment": false,
      "Quantity": 130
    }
  ],
  "Portfolio": [
    {
      "Ticker": "AAPL",
      "Quantity": 130,
      "AveragePrice": 254.05,
      "UnreazliedProfit": -4.9
    },
    {
      "Ticker": "USD",
      "Quantity": 34053.79
    }
  ]
}
</pre></div>
