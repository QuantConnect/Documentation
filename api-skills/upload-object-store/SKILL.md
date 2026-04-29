---
name: upload-object-store
description: >
  Uploads a local file to the QuantConnect Object Store via REST API, making it accessible
  for cloud backtests and algorithms. Invoke when any skill or the user needs to upload a
  local file to QC Object Store, or when phrases like "upload to object store",
  "put this file in the object store", or "make this local file available in QC" appear.
---

# /upload-object-store -- Upload Local File to QC Object Store

Uploads a local file to the QuantConnect Object Store using the REST API.

---

## Step 1 -- Gather inputs

Required information (ask if not already provided):
- Local file path (absolute path to the file to upload)
- Object store key (the path/name the file will have in the store, e.g. `custom-data/nifty.json`)

---

## Step 2 -- Read credentials and organization ID

**Credentials** -- read `~/.lean/credentials`:
- `user-id` -> USER_ID
- `api-token` -> API_TOKEN

**Organization ID** -- ask the user for their organization ID if not already known.
It can be found at quantconnect.com -> Account -> Organization.

---

## Step 3 -- Upload via REST API

Build and run a single Bash command with all values substituted in:

```bash
TIMESTAMP=$(date +%s)
USER_ID="<user-id from credentials>"
API_TOKEN="<api-token from credentials>"
ORG_ID="<organization ID>"
OBJECT_KEY="<object store key>"
LOCAL_PATH="<local file path, forward slashes, e.g. /c/tmp/nifty.json>"
HASH=$(printf '%s:%s' "$API_TOKEN" "$TIMESTAMP" | sha256sum | cut -d' ' -f1)
CREDS=$(printf '%s:%s' "$USER_ID" "$HASH" | base64 -w 0)
curl -s -X POST "https://www.quantconnect.com/api/v2/object/set" \
  -H "Authorization: Basic $CREDS" \
  -H "Timestamp: $TIMESTAMP" \
  -F "organizationId=$ORG_ID" \
  -F "key=$OBJECT_KEY" \
  -F "objectData=@$LOCAL_PATH"
```

Note: On Windows/Git Bash, convert the local path to a Unix-style path (e.g. `C:/tmp/file.json` -> `/c/tmp/file.json`).

---

## Step 4 -- Verify response

The response is JSON. Check for `"success": true`. If false, report the `errors` array to the
user and stop.

On success, report:
- Object store key: `<key>`
- Organization: `<ORG_ID>`
- File is now accessible in QC cloud via `SubscriptionTransportMedium.OBJECT_STORE`
