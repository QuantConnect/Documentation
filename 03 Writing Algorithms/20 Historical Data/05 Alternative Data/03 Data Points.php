<?
$datasetClass = "Fred";
?>

<p class='csharp'>
  To get a list of historical alternative data, call the <code>History&lt;<span class='placeholder-text'>alternativeDataClass</span>&gt;</code> method with the dataset <code>Symbol</code>.
</p>

<p class='python'>
  To get historical alternative data points, call the <code>history</code> method with the dataset <code>Symbol</code>.
  This method returns a DataFrame that contains the data point attributes.
</p>

<div class="section-example-container">
    <pre class="csharp">public class AlternativeDataHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 20);
        // Get the Symbol of a dataset.
        var datasetSymbol = AddData&lt;<?=$datasetClass?>&gt;("RVXCLS").Symbol;
        // Get the trailing 5 days of <?=$datasetClass?> data.
        var history = History&lt;<?=$datasetClass?>&gt;(datasetSymbol, 5, Resolution.Daily);
        // Iterate through the historical data points.
        foreach (var dataPoint in history)
        {
            var t = dataPoint.EndTime;
            var value = dataPoint.Value;
        }
    }
}</pre>
    <pre class="python">class AlternativeDataHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 20)
        # Get the Symbol of a dataset.
        dataset_symbol = self.add_data(Fred, 'RVXCLS').symbol
        # Get the trailing 5 days of Fred data in DataFrame format.
        history = self.history(dataset_symbol, 5, Resolution.DAILY)</pre>
</div>

<div class="dataframe-wrapper">
  <table class="dataframe python">
    <thead>
      <tr style="text-align: right;">
        <th></th>
        <th></th>
        <th>value</th>
      </tr>
      <tr>
        <th>symbol</th>
        <th>time</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th rowspan="4" valign="top">RVXCLS.Fred</th>
        <th>2024-12-17</th>
        <td>23.02</td>
      </tr>
      <tr>
        <th>2024-12-18</th>
        <td>24.01</td>
      </tr>
      <tr>
        <th>2024-12-19</th>
        <td>32.76</td>
      </tr>
      <tr>
        <th>2024-12-20</th>
        <td>29.90</td>
      </tr>
    </tbody>
  </table>
</div>


<div class="python section-example-container">
    <pre class="python"># Calculate the dataset's rate of change.
roc = history.pct_change().iloc[1:]</pre>
</div>

<div class="dataframe-wrapper">
  <table class="dataframe python">
    <thead>
      <tr style="text-align: right;">
        <th></th>
        <th></th>
        <th>value</th>
      </tr>
      <tr>
        <th>symbol</th>
        <th>time</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th rowspan="3" valign="top">RVXCLS.Fred</th>
        <th>2024-12-18</th>
        <td>0.043006</td>
      </tr>
      <tr>
        <th>2024-12-19</th>
        <td>0.364431</td>
      </tr>
      <tr>
        <th>2024-12-20</th>
        <td>-0.087302</td>
      </tr>
    </tbody>
  </table>
</div>


<p class='python'>
  If you intend to use the data in the DataFrame to create <code><span class='placeholder-text'>alternativeDataClass</span></code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN consumes unnecessary computational resources populating the DataFrame.  
  To get a list of dataset objects instead of a DataFrame, call the <code>history[<span class='placeholder-text'>alternativeDataClass</span>]</code> method.
</p>

<div class="python section-example-container">
    <pre class="python"># Get the trailing 5 days of <?=$datasetClass?> data for an asset in <?=$datasetClass?> format. 
history = self.history[<?=$datasetClass?>](dataset_symbol, 5, Resolution.DAILY)
# Iterate through the historical data points.
for data_point in history:
    t = data_point.end_time
    value = data_point.value</pre>
</div>


  <p>
    Some alternative datasets provide multiple entries per asset per <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>time step</a>. 
    For example, the <a href='/datasets/regalytics-us-regulatory-alerts'>US Regulatory Alerts</a> dataset can provide multiple alerts per day.
    <span class='csharp'>In this case, use a nested loop to access all the data point attributes.</span>
    <span class='python'>In this case, to organize the data into a DataFrame, set the <code>flatten</code> argument to <code>True</code>.</span>
  </p>

  <div class="section-example-container">
    <pre class="python">class RegalyticsHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 20)      
        # Get the all the Regalytics articles that were published over the last day, organized in a DataFrame.
        dataset_symbol = self.add_data(RegalyticsRegulatoryArticles, "REG").symbol
        history = self.history(dataset_symbol, 1, Resolution.DAILY, flatten=True)</pre>
    <pre class="csharp">public class RegalyticsHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 20);
        // Get the all the Regalytics articles that were published over the last day.
        var datasetSymbol = AddData&lt;RegalyticsRegulatoryArticles&gt;("REG").Symbol;
        var history = History&lt;RegalyticsRegulatoryArticles&gt;(datasetSymbol, 1, Resolution.Daily);
        // Iterate through each day of articles.
        foreach (var articles in history)
        {
            var t = articles.EndTime;
            // Get the unique alert types for this day.
            var altertTypes = articles.Select(article => (article as RegalyticsRegulatoryArticle).AlertType).Distinct().ToList();
        }
    }
}</pre>
  </div>

  <div class="dataframe-wrapper">
    <table class="dataframe python">
      <thead>
        <tr style="text-align: right;">
          <th></th>
          <th></th>
          <th>agencies</th>
          <th>alerttype</th>
          <th>...</th>
          <th>time</th>
          <th>title</th>
        </tr>
        <tr>
          <th>time</th>
          <th>symbol</th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th rowspan="11" valign="top">2024-12-20</th>
          <th>REG.RegalyticsRegulatoryArticles</th>
          <td>[Consumer Financial Protection Bureau]</td>
          <td>Complaint</td>
          <td>...</td>
          <td>2024-12-21</td>
          <td>Complaint Filed: Equifax, Inc.</td>
        </tr>
        <tr>
          <th>REG.RegalyticsRegulatoryArticles</th>
          <td>[Consumer Financial Protection Bureau]</td>
          <td>Complaint</td>
          <td>...</td>
          <td>2024-12-21</td>
          <td>Complaint Filed: Experian Information Solutions, Inc.</td>
        </tr>
        <tr>
          <th>REG.RegalyticsRegulatoryArticles</th>
          <td>[Consumer Financial Protection Bureau]</td>
          <td>Complaint</td>
          <td>...</td>
          <td>2024-12-21</td>
          <td>Complaint Filed: Equifax, Inc.</td>
        </tr>
        <tr>
          <th>REG.RegalyticsRegulatoryArticles</th>
          <td>[Consumer Financial Protection Bureau]</td>
          <td>Complaint</td>
          <td>...</td>
          <td>2024-12-21</td>
          <td>Complaint Filed: Transunion Intermediate Holdings, Inc.</td>
        </tr>
        <tr>
          <th>REG.RegalyticsRegulatoryArticles</th>
          <td>[Consumer Financial Protection Bureau]</td>
          <td>Complaint</td>
          <td>...</td>
          <td>2024-12-21</td>
          <td>Complaint Filed: Transunion Intermediate Holdings, Inc.</td>
        </tr>
        <tr>
          <th>...</th>
          <td>...</td>
          <td>...</td>
          <td>...</td>
          <td>...</td>
          <td>...</td>
        </tr>
        <tr>
          <th>REG.RegalyticsRegulatoryArticles</th>
          <td>[Consumer Financial Protection Bureau]</td>
          <td>Complaint</td>
          <td>...</td>
          <td>2024-12-21</td>
          <td>Complaint Filed: Equifax, Inc.</td>
        </tr>
        <tr>
          <th>REG.RegalyticsRegulatoryArticles</th>
          <td>[Consumer Financial Protection Bureau]</td>
          <td>Complaint</td>
          <td>...</td>
          <td>2024-12-21</td>
          <td>Complaint Filed: Equifax, Inc.</td>
        </tr>
        <tr>
          <th>REG.RegalyticsRegulatoryArticles</th>
          <td>[Consumer Financial Protection Bureau]</td>
          <td>Complaint</td>
          <td>...</td>
          <td>2024-12-21</td>
          <td>Complaint Filed: Equifax, Inc.</td>
        </tr>
        <tr>
          <th>REG.RegalyticsRegulatoryArticles</th>
          <td>[Consumer Financial Protection Bureau]</td>
          <td>Complaint</td>
          <td>...</td>
          <td>2024-12-21</td>
          <td>Complaint Filed: Equifax, Inc.</td>
        </tr>
        <tr>
          <th>REG.RegalyticsRegulatoryArticles</th>
          <td>[Consumer Financial Protection Bureau]</td>
          <td>Complaint</td>
          <td>...</td>
          <td>2024-12-21</td>
          <td>Complaint Filed: Equifax, Inc.</td>
        </tr>
      </tbody>
    </table>
  </div>


  <div class="python section-example-container">
    <pre class="python"># Get all the unique alert types from the Regalytics articles.
alert_types = history.alerttype.unique()</pre>
  </div>

    <div class="python section-example-container">
    <pre>array(['Complaint', 'Press release', 'Event', 'Litigation Release',
       'Grant Information', 'Media Release', 'News', 'Announcement',
       'Transcript', 'Decree', 'Decision', 'Regulation',
       'Executive Order', 'Media Advisory', 'Disaster Press Release',
       'Notice', 'Procurement', 'Meeting', 'News release', 'Contract',
       'Publication', 'Blog', 'Tabled Document', 'Resolution', 'Bill',
       'Concurrent Resolution', 'Opinions and Adjudicatory Orders',
       'Proposed rule', 'Technical Notice', 'Sanction', 'Order',
       'Statement', 'Rule', 'enforcement action', 'Report',
       'Statement|Release',
       'AWCs (Letters of Acceptance, Waiver, and Consent)'], dtype=object)</pre>
    </div>


