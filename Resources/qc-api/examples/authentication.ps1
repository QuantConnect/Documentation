$yourUserId = if ($env:QC_USER_ID) { $env:QC_USER_ID } else { "0" }
$yourApiToken = if ($env:QC_API_TOKEN) { $env:QC_API_TOKEN } else { "_____" }

# Get timestamp
$timestamp = [Math]::Floor((([DateTimeOffset]::UtcNow).ToUnixTimeSeconds()))
$timeStampedToken = "${yourApiToken}:${timestamp}"

# Get hashed API token
$hasher = [System.Security.Cryptography.SHA256]::Create()
$hashBytes = $hasher.ComputeHash([System.Text.Encoding]::UTF8.GetBytes($timeStampedToken))
$hash = -join ($hashBytes | ForEach-Object { $_.ToString("x2") })
$authentication = [Convert]::ToBase64String([System.Text.Encoding]::UTF8.GetBytes("${yourUserId}:${hash}"))

# Make POST request with authentication headers
$headers = @{
    "Authorization" = "Basic $authentication"
    "Timestamp" = "$timestamp"
}
$response = Invoke-RestMethod -Uri "https://www.quantconnect.com/api/v2/authenticate" -Method Post -Headers $headers -ContentType "application/json" -Body "{}"
Write-Host "Response: $($response | ConvertTo-Json)"