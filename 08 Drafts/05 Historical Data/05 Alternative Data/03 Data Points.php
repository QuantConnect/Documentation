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
      <th>announcementurl</th>
      <th>classification</th>
      <th>createdat</th>
      <th>federalregisternumber</th>
      <th>filingtype</th>
      <th>id</th>
      <th>latestupdate</th>
      <th>originalpublicationdate</th>
      <th>sector</th>
      <th>states</th>
      <th>status</th>
      <th>summary</th>
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
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
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
      <td>https://www.consumerfinance.gov/data-research/consumer-complaints/search/detail/11178809</td>
      <td>Federal</td>
      <td>2024-12-19 04:33:07.165112</td>
      <td>None</td>
      <td>Single</td>
      <td>19372CFPB1117880920241216</td>
      <td>2024-12-18</td>
      <td>2024-12-16</td>
      <td>[[[id, 6], [name, Employment]], [[id, 1], [name, Financial]], [[id, 15], [name, Retail]]]</td>
      <td>[]</td>
      <td>New</td>
      <td>The Consumer Financial Protection Bureau received a complaint against Equifax, Inc., on December 16, 2024, via web with the following details: consumer's state: NY; consumer's zip: 10457; product: credit reporting or other personal consumer reports; sub-product: credit reporting; issue: incorrect information on your report; sub-issue: information belongs to someone else; timely response: yes; company response to consumer: in progress; company public response: N/A.</td>
      <td>2024-12-21</td>
      <td>Complaint Filed: Equifax, Inc.</td>
    </tr>
    <tr>
      <th>REG.RegalyticsRegulatoryArticles</th>
      <td>[Consumer Financial Protection Bureau]</td>
      <td>Complaint</td>
      <td>https://www.consumerfinance.gov/data-research/consumer-complaints/search/detail/11178808</td>
      <td>Federal</td>
      <td>2024-12-19 04:33:06.409436</td>
      <td>None</td>
      <td>Single</td>
      <td>19372CFPB1117880820241216</td>
      <td>2024-12-18</td>
      <td>2024-12-16</td>
      <td>[[[id, 6], [name, Employment]], [[id, 1], [name, Financial]], [[id, 15], [name, Retail]]]</td>
      <td>[]</td>
      <td>New</td>
      <td>The Consumer Financial Protection Bureau received a complaint against Experian Information Solutions, Inc., on December 16, 2024, via web with the following details: consumer's state: TX; consumer's zip: 76011; product: credit reporting or other personal consumer reports; sub-product: credit reporting; issue: incorrect information on your report; sub-issue: account information incorrect; timely response: yes; company response to consumer: in progress; company public response: N/A.</td>
      <td>2024-12-21</td>
      <td>Complaint Filed: Experian Information Solutions, Inc.</td>
    </tr>
    <tr>
      <th>REG.RegalyticsRegulatoryArticles</th>
      <td>[Consumer Financial Protection Bureau]</td>
      <td>Complaint</td>
      <td>https://www.consumerfinance.gov/data-research/consumer-complaints/search/detail/11178805</td>
      <td>Federal</td>
      <td>2024-12-19 04:33:04.012876</td>
      <td>None</td>
      <td>Single</td>
      <td>19372CFPB1117880520241216</td>
      <td>2024-12-18</td>
      <td>2024-12-16</td>
      <td>[[[id, 1], [name, Financial]], [[id, 15], [name, Retail]], [[id, 22], [name, Taxes]]]</td>
      <td>[]</td>
      <td>New</td>
      <td>The Consumer Financial Protection Bureau received a complaint against Equifax, Inc., on December 16, 2024, via web with the following details: consumer's state: FL; consumer's zip: 33056; product: credit reporting or other personal consumer reports; sub-product: credit reporting; issue: improper use of your report; sub-issue: reporting company used your report improperly; timely response: yes; company response to consumer: in progress; company public response: N/A.</td>
      <td>2024-12-21</td>
      <td>Complaint Filed: Equifax, Inc.</td>
    </tr>
    <tr>
      <th>REG.RegalyticsRegulatoryArticles</th>
      <td>[Consumer Financial Protection Bureau]</td>
      <td>Complaint</td>
      <td>https://www.consumerfinance.gov/data-research/consumer-complaints/search/detail/11178813</td>
      <td>Federal</td>
      <td>2024-12-19 04:33:09.563813</td>
      <td>None</td>
      <td>Single</td>
      <td>19372CFPB1117881320241216</td>
      <td>2024-12-18</td>
      <td>2024-12-16</td>
      <td>[[[id, 1], [name, Financial]], [[id, 15], [name, Retail]], [[id, 22], [name, Taxes]]]</td>
      <td>[]</td>
      <td>New</td>
      <td>The Consumer Financial Protection Bureau received a complaint against Transunion Intermediate Holdings, Inc., on December 16, 2024, via web with the following details: consumer's state: TX; consumer's zip: 77048; product: credit reporting or other personal consumer reports; sub-product: credit reporting; issue: improper use of your report; sub-issue: reporting company used your report improperly; timely response: yes; company response to consumer: in progress; company public response: N/A.</td>
      <td>2024-12-21</td>
      <td>Complaint Filed: Transunion Intermediate Holdings, Inc.</td>
    </tr>
    <tr>
      <th>REG.RegalyticsRegulatoryArticles</th>
      <td>[Consumer Financial Protection Bureau]</td>
      <td>Complaint</td>
      <td>https://www.consumerfinance.gov/data-research/consumer-complaints/search/detail/11178811</td>
      <td>Federal</td>
      <td>2024-12-19 04:33:08.745803</td>
      <td>None</td>
      <td>Single</td>
      <td>19372CFPB1117881120241216</td>
      <td>2024-12-18</td>
      <td>2024-12-16</td>
      <td>[[[id, 23], [name, Accounting]], [[id, 1], [name, Financial]], [[id, 15], [name, Retail]], [[id, 22], [name, Taxes]]]</td>
      <td>[]</td>
      <td>New</td>
      <td>The Consumer Financial Protection Bureau received a complaint against Transunion Intermediate Holdings, Inc., on December 16, 2024, via web with the following details: consumer's state: NY; consumer's zip: 11419; product: credit reporting or other personal consumer reports; sub-product: credit reporting; issue: incorrect information on your report; sub-issue: information belongs to someone else; timely response: yes; company response to consumer: in progress; company public response: N/A.</td>
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
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
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
      <td>https://www.consumerfinance.gov/data-research/consumer-complaints/search/detail/11175965</td>
      <td>Federal</td>
      <td>2024-12-19 04:20:30.693391</td>
      <td>None</td>
      <td>Single</td>
      <td>19372CFPB1117596520241217</td>
      <td>2024-12-18</td>
      <td>2024-12-17</td>
      <td>[[[id, 1], [name, Financial]], [[id, 15], [name, Retail]], [[id, 22], [name, Taxes]]]</td>
      <td>[]</td>
      <td>New</td>
      <td>The Consumer Financial Protection Bureau received a complaint against Equifax, Inc., on December 17, 2024, via web with the following details: consumer's state: TX; consumer's zip: 78130; product: credit reporting or other personal consumer reports; sub-product: credit reporting; issue: improper use of your report; sub-issue: reporting company used your report improperly; timely response: yes; company response to consumer: in progress; company public response: N/A.</td>
      <td>2024-12-21</td>
      <td>Complaint Filed: Equifax, Inc.</td>
    </tr>
    <tr>
      <th>REG.RegalyticsRegulatoryArticles</th>
      <td>[Consumer Financial Protection Bureau]</td>
      <td>Complaint</td>
      <td>https://www.consumerfinance.gov/data-research/consumer-complaints/search/detail/11175956</td>
      <td>Federal</td>
      <td>2024-12-19 04:20:29.935351</td>
      <td>None</td>
      <td>Single</td>
      <td>19372CFPB1117595620241217</td>
      <td>2024-12-18</td>
      <td>2024-12-17</td>
      <td>[[[id, 1], [name, Financial]], [[id, 15], [name, Retail]], [[id, 22], [name, Taxes]]]</td>
      <td>[]</td>
      <td>New</td>
      <td>The Consumer Financial Protection Bureau received a complaint against Equifax, Inc., on December 17, 2024, via web with the following details: consumer's state: FL; consumer's zip: 33304; product: credit reporting or other personal consumer reports; sub-product: credit reporting; issue: improper use of your report; sub-issue: reporting company used your report improperly; timely response: yes; company response to consumer: in progress; company public response: N/A.</td>
      <td>2024-12-21</td>
      <td>Complaint Filed: Equifax, Inc.</td>
    </tr>
    <tr>
      <th>REG.RegalyticsRegulatoryArticles</th>
      <td>[Consumer Financial Protection Bureau]</td>
      <td>Complaint</td>
      <td>https://www.consumerfinance.gov/data-research/consumer-complaints/search/detail/11175970</td>
      <td>Federal</td>
      <td>2024-12-19 04:20:31.513255</td>
      <td>None</td>
      <td>Single</td>
      <td>19372CFPB1117597020241217</td>
      <td>2024-12-18</td>
      <td>2024-12-17</td>
      <td>[[[id, 1], [name, Financial]], [[id, 15], [name, Retail]], [[id, 22], [name, Taxes]]]</td>
      <td>[]</td>
      <td>New</td>
      <td>The Consumer Financial Protection Bureau received a complaint against Equifax, Inc., on December 17, 2024, via web with the following details: consumer's state: WA; consumer's zip: 98373; product: credit reporting or other personal consumer reports; sub-product: credit reporting; issue: improper use of your report; sub-issue: credit inquiries on your report that you don't recognize; timely response: yes; company response to consumer: in progress; company public response: N/A.</td>
      <td>2024-12-21</td>
      <td>Complaint Filed: Equifax, Inc.</td>
    </tr>
    <tr>
      <th>REG.RegalyticsRegulatoryArticles</th>
      <td>[Consumer Financial Protection Bureau]</td>
      <td>Complaint</td>
      <td>https://www.consumerfinance.gov/data-research/consumer-complaints/search/detail/11175973</td>
      <td>Federal</td>
      <td>2024-12-19 04:20:33.124772</td>
      <td>None</td>
      <td>Single</td>
      <td>19372CFPB1117597320241217</td>
      <td>2024-12-18</td>
      <td>2024-12-17</td>
      <td>[[[id, 1], [name, Financial]], [[id, 15], [name, Retail]], [[id, 22], [name, Taxes]]]</td>
      <td>[]</td>
      <td>New</td>
      <td>The Consumer Financial Protection Bureau received a complaint against Equifax, Inc., on December 17, 2024, via web with the following details: consumer's state: NJ; consumer's zip: 08846; product: credit reporting or other personal consumer reports; sub-product: credit reporting; issue: improper use of your report; sub-issue: reporting company used your report improperly; timely response: yes; company response to consumer: in progress; company public response: N/A.</td>
      <td>2024-12-21</td>
      <td>Complaint Filed: Equifax, Inc.</td>
    </tr>
    <tr>
      <th>REG.RegalyticsRegulatoryArticles</th>
      <td>[Consumer Financial Protection Bureau]</td>
      <td>Complaint</td>
      <td>https://www.consumerfinance.gov/data-research/consumer-complaints/search/detail/11175972</td>
      <td>Federal</td>
      <td>2024-12-19 04:20:32.334464</td>
      <td>None</td>
      <td>Single</td>
      <td>19372CFPB1117597220241217</td>
      <td>2024-12-18</td>
      <td>2024-12-17</td>
      <td>[[[id, 1], [name, Financial]], [[id, 15], [name, Retail]], [[id, 22], [name, Taxes]]]</td>
      <td>[]</td>
      <td>New</td>
      <td>The Consumer Financial Protection Bureau received a complaint against Equifax, Inc., on December 17, 2024, via web with the following details: consumer's state: DE; consumer's zip: 19802; product: credit reporting or other personal consumer reports; sub-product: credit reporting; issue: improper use of your report; sub-issue: reporting company used your report improperly; timely response: yes; company response to consumer: in progress; company public response: N/A.</td>
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


