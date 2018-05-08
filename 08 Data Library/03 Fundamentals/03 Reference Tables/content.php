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
               $type = "<a href='https://github.com/QuantConnect/Lean/tree/master/Common/Data/Fundamental/Generated'>$type (MultiPeriodField)</a>";
               $periodExample = ".OneMonth";
            }
            
            $tooltip = htmlentities($property['description']);
            
            echo "
<tr>
			<td width='30%'><code>$name</code><br/><i style='font-size: 0.8em'>$type</i>
			</td>
			<td>
				<p>$tooltip</p>
				<p><code>fine.$fieldName.$name{$periodExample}</code>
				</p>
			</td>
		</tr>";
        }
    ?>
	</tbody>
</table>
<?php } ?>