# Import from https://www.quantconnect.com/docs/v2/research-environment/object-store/storing-data


<br>-What is reading data:
<br>&nbsp;&nbsp;&nbsp;&nbsp;- Reading an entry in the ObjectStore by providing the key it was saved under
<br>-Why read data:
<br>&nbsp;&nbsp;&nbsp;&nbsp;- Saving algorithm variable values between deployments
<br>&nbsp;&nbsp;&nbsp;&nbsp;- Transporting data between the backtesting environment and the research environment
<br>&nbsp;&nbsp;&nbsp;&nbsp;- Training machine learning models in the research environment before deploying them to live trading
<br>-How(?):
<br>&nbsp;&nbsp;&nbsp;&nbsp; -Using Read, ReadBytes, ReadJson, ReadXml
<br>&nbsp;&nbsp;&nbsp;&nbsp; -Method depends on data being stored
<br>&nbsp;&nbsp;&nbsp;&nbsp; -Need to provide key
<br>&nbsp;&nbsp;&nbsp;&nbsp;-<snippet example of using each method>
