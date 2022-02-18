<p>
   QuantConnect recommends developing an algorithmic trading strategy based on a central hypothesis. 
   An algorithm hypothesis should be developed at the start of your research, and the remaining time spent exploring how to test your theory. If you find yourself deviating from your core theory, or introducing code that isn't based around that hypothesis, you should stop and go back to thesis development. 
</p>
<p>
   Hypothesis development is somewhat of an art and requires creativity and great observation skills. It is one of the most powerful skills a quant can learn. We recommend that an algorithm hypothesis follow the pattern of cause and effect. Your aim should be to express your strategy in the following sentence:
</p>
<blockquote style="padding-left: 10px; font-style: italic;">
   A change in {cause} leads to an {effect}.
</blockquote>
<p></p>
<p>
   You can search for inspiration by considering causes from your own experience, intuition, or the media. Generally, causes of financial market movements fall into three categories: human psychology, real-world events/fundamentals, or invisible financial actions. 
</p>
<p>
   Consider the following examples:
</p>
<style>
   .cause-examples .center{ 
   text-align: center;
   }
   .cause-examples .symbol{ 
   text-align: center;
   padding-top: 15px;
   }
   .cause-examples .fa-chevron-right {
   font-size: 2em;
   }
   .cause-examples td i.fa-external-link  {
   text-align: center;
   margin-left: 15px;
   }
</style>
<table class="table cause-examples">
   <thead>
      <tr>
         <th width="40%" style="text-align: center">Cause</th>
         <th class="center" width="100px;" style="font-style: italic;">leads to</th>
         <th width="40%" style="text-align: center">Effect</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td>Share class stocks are the same company so any price divergence is irrational...</td>
         <td style="text-align: center; padding-top: 15px;"><i class="fa fa-chevron-right"></i></td>
         <td>A perfect pairs trade. Since they are the same company the price will revert.</td>
      </tr>
      <tr>
         <td>New stock addition to the S&amp;P500 Index causes fund managers to buy up stock...</td>
         <td style="text-align: center; padding-top: 15px;"><i class="fa fa-chevron-right"></i></td>
         <td>An increase in the price of the new asset in the universe from buying pressure.</td>
      </tr>
      <tr>
         <td>Increase in sunshine-hours increases the production of oranges...</td>
         <td style="text-align: center; padding-top: 15px;"><i class="fa fa-chevron-right"></i></td>
         <td>An increase in the supply of oranges, decreasing price of Orange Juice Futures.</td>
      </tr>
      <tr>
         <td>Allegations of fraud by the CEO causes investor faith in the stock to fall...</td>
         <td style="text-align: center; padding-top: 15px;"><i class="fa fa-chevron-right"></i></td>
         <td>A collapse of stock prices for the company as people panic.</td>
      </tr>
      <tr>
         <td>FDA approval of a new drug opens up new markets for the pharmaceutical company...</td>
         <td style="text-align: center; padding-top: 15px;"><i class="fa fa-chevron-right"></i></td>
         <td>A jump in stock prices for the company.</td>
      </tr>
      <tr>
         <td>Increasing federal interest rates restrict lending from banks, raising interest rates...</td>
         <td style="text-align: center; padding-top: 15px;"><i class="fa fa-chevron-right"></i></td>
         <td>Restricted REIT leverage and lower REIT ETF returns.</td>
      </tr>
   </tbody>
</table>
<p>
   There are millions of potential alpha strategies to explore, each of them a candidate for an algorithm. Once you have chosen a strategy, we recommend exploring it no more than 8-32 hours, depending on your coding ability.
</p>