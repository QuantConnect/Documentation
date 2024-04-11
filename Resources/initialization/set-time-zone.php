<p>
    <?=$writingAlgorithms ? "LEAN supports international trading across multiple time zones and markets, so it needs a reference time zone for the algorithm to set the <code>Time</code>." : "" ?> The default time zone is Eastern Time (ET), which is UTC-4 in summer and UTC-5 in winter. To set a different time zone, call the <code>SetTimeZone</code> method. This method accepts either a string following the <a rel='nofollow' target='_blank' href='https://en.wikipedia.org/wiki/List_of_tz_database_time_zones'>IANA Time Zone database</a> convention or a <a rel='nofollow' target='_blank' href='https://nodatime.org/'>NodaTime</a>.DateTimeZone object. If you pass a string, the method converts it to a <code>NodaTime.DateTimeZone</code> object. The <code>TimeZones</code> class provides the following helper attributes to create <code>NodaTime.DateTimeZone</code> objects:
</p>

<div data-tree='QuantConnect.TimeZones'></div>

<div class='section-example-container'>
<pre class='csharp'><?=$writingAlgorithms ? "" : "qb."?>SetTimeZone("Europe/London");
<?=$writingAlgorithms ? "" : "qb."?>SetTimeZone(NodaTime.DateTimeZone.Utc);
<?=$writingAlgorithms ? "" : "qb."?>SetTimeZone(TimeZones.Chicago);
</pre>
<pre class='python'><?=$writingAlgorithms ? "self" : "qb"?>.set_time_zone("Europe/London")
<?=$writingAlgorithms ? "self" : "qb"?>.set_time_zone(TimeZones.chicago)
</pre>
</div>
