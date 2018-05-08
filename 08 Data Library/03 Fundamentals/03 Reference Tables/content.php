<?php
$fields = file_get_contents("https://www.quantconnect.com/services/fundamentals");
$decoded = json_decode($fields, true);
foreach ($decoded['fundamentals'] as $field) {
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
            $name = $property['name'];
            $type = $property['type'];
            $isMultiPeriod = strpos($type, "QuantConnect.Data.Fundamental.") !== false;
            
            $type = str_replace("System.", "", $type);
            $type = str_replace("QuantConnect.Data.Fundamental.", "", $type);
            
            if ($isMultiPeriod) $type = "<a href='https://github.com/QuantConnect/Lean/tree/master/Common/Data/Fundamental/Generated'>$type : MultiPeriodField</a>";
            
            $tooltip = htmlentities($property['description']);
            
            echo "
<tr>
			<td width='30%'>$name <br/>
				<small>$type</small>
			</td>
			<td>$tooltip</td>
		</tr>";
        }
    ?>
	</tbody>
</table>
<?php } ?>