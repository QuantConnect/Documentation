<p>
The following reference tables detail the specific objects available for use in your QuantConnect Fine Universe filter. These properties are subsets of the <code>FineFundamental</code> object presented to your universe filter each day.
</p>
<?php
$fields = file_get_contents("https://www.quantconnect.com/services/fundamentals"); 
$decoded = json_decode($fields, true);
foreach ($decoded['fundamentals'] as $field) 
{
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
            $propertyName = $property['name'];
            $isMultiPeriod = $property['multiperiodfield'];
            $children = $property['child-properties'];
            
            $type = $property['type'];
            $type = str_replace("System.", "", $type);
            $type = str_replace("QuantConnect.Data.Fundamental.", "", $type);
            
            if ($isMultiPeriod) {
               $type = "<a style='color: #747f8e !important;' href='https://raw.githubusercontent.com/QuantConnect/Lean/master/Common/Data/Fundamental/Generated/MultiPeriodValueTypes.cs'>MultiPeriodField</a>";
               $periodExample = ".OneMonth";
            }
            
            $description = htmlentities($property['description']);
            
            if ( count($children) == 0) {
                ?>
		<tr>
			<td width='30%'><code>
				<?=$propertyName?></code><br/>
				<p>$type</p>
			</td>
			<td>
				<p>
					<?=$description?>
				</p>
				<pre class='prettyprint' style='border: none !important; background: transparent; font-size: 1em;'>fine.
					<?php echo $fieldName.$propertyName; ?>
				</pre>
			</td>
		</tr>
		<?php
            } else {
                foreach($children as $child) {
                    ?>
		<tr>
			<td width='30%'> CHILD 
                            <code>
				<?=print_r($child, true); ?></code><br/>
				<p>$type</p>
			</td>
			<td>
				<p>CHILD 
					<?=$description?>
				</p>
				<pre class='prettyprint' style='border: none !important; background: transparent; font-size: 1em;'>fine.
					<?php echo $fieldName.$propertyName; ?>
				</pre>
			</td>
		</tr>
		<?php
                }
            }
        }
    ?>
	</tbody>
</table>
<?php } ?>
