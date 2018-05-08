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
            $tooltip = htmlentities($property['description']);
            echo "
<tr>
			<td width='30%'>$name</td>
			<td>$tooltip</td>
		</tr>";
        }
    ?>
	</tbody>
</table>
<?php } ?>