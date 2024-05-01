<p>
  Live data takes time to travel from the source to your algorithm. 
  The QuantConnect latencies vary depending on the data provider but for US Equities we have a latency of 5-40 milliseconds. 
  A much more significant source of latency is the round trip order times from brokers which can vary from 100ms to 5 seconds. 
  QuantConnect is not intended for high-frequency trading, but we have integrations to high-speed brokers if required.  
</p>
