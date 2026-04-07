YOUR_USER_ID=${QC_USER_ID:-0}
YOUR_API_TOKEN=${QC_API_TOKEN:-_____}

# Get timestamp
TIMESTAMP=$(date +%s)
TIME_STAMPED_TOKEN="${YOUR_API_TOKEN}:${TIMESTAMP}"

# Get hashed API token
HASHED_TOKEN=$(echo -n "$TIME_STAMPED_TOKEN" | sha256sum | cut -d ' ' -f 1)
AUTHENTICATION=$(echo -n "${YOUR_USER_ID}:${HASHED_TOKEN}" | base64 -w 0)

# Make POST request with authentication headers
curl -X POST "https://www.quantconnect.com/api/v2/authenticate" \
    -H "Authorization: Basic ${AUTHENTICATION}" \
    -H "Timestamp: ${TIMESTAMP}" \
    -H "Content-Type: application/json" \
    -d '{}'