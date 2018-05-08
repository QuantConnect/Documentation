<p>
The following reference tables detail the specific objects available for use in your QuantConnect Fine Universe filter. These properties are subsets of the <code>FineFundamental</code> object presented to your universe filter each day.
</p>
<?php
$fields = file_get_contents("https://www.quantconnect.com/services/fundamentals");
$decoded = json_decode($fields, true);
foreach ($decoded['fundamentals'] as $field) {
    $fieldName = $field['name'];
    ?>
<table class="table qc-table table-itemized table-reflow">
	<thead>
		<tr>
			<th colspan="2">
				<?php echo $field['name']; ?>
			</th>
		</tr>
	</thead>
	<tbody>
		<?php
        foreach ($field['properties'] as $property)
        {
            $periodExample = "";
            $name = $property['name'];
            $type = $property['type'];
            $isMultiPeriod = strpos($type, "QuantConnect.Data.Fundamental.") !== false;
            
            $type = str_replace("System.", "", $type);
            $type = str_replace("QuantConnect.Data.Fundamental.", "", $type);
            
            if ($isMultiPeriod) {
               $type = "<a style='color: #747f8e !important;' href='https://raw.githubusercontent.com/QuantConnect/Lean/master/Common/Data/Fundamental/Generated/MultiPeriodValueTypes.cs'>MultiPeriodField</a>";
               $periodExample = ".OneMonth";
            }
            
            $tooltip = htmlentities($property['description']);
            
            echo "
<tr>
			<td width='30%'><code>$name</code><br/>
				<p>$type</p>
			</td>
			<td>
				<p>$tooltip</p>
				<pre class="prettyprint" style='border: none !important; background: transparent;'>fine.$fieldName.$name{$periodExample}</pre>
			</td>
		</tr>";
        }
    ?>
	</tbody>
</table>
<?php } ?>