<?php
$getStorageQuotasText = function($context)
{
    echo "
<p>If you {$context} locally, you can store as much data as your hardware will allow. If you {$context} in QuantConnect Cloud, you must stay within your <a href='/docs/v2/cloud-platform/data-storage#03-Storage-Sizes'>storage quota</a>. If you need more storage space, <a href='/docs/v2/cloud-platform/data-storage#07-Edit-Storage-Plan'>edit your storage plan</a>.</p>    
    
"; 
  
}

?>
