// Generate a timestamped SHA-256 hashed API token for secure authentication
using System.Security.Cryptography;
using System.Text;

// Set the QC_USER_ID and QC_API_TOKEN environment variables with values from https://www.quantconnect.com/settings/.
var yourUserId = Environment.GetEnvironmentVariable("QC_USER_ID") ?? "0";
var yourApiToken = Environment.GetEnvironmentVariable("QC_API_TOKEN") ?? "_____";

Dictionary<string, string> GetHeaders()
{
    // Get timestamp
    var timestamp = ((DateTimeOffset)DateTime.UtcNow).ToUnixTimeSeconds().ToString();
    var timeStampedToken = $"{yourApiToken}:{timestamp}";

    // Get hashed API token
    var hashBytes = SHA256.HashData(Encoding.UTF8.GetBytes(timeStampedToken));
    var hash = BitConverter.ToString(hashBytes).Replace("-", "").ToLowerInvariant();
    var authentication = Convert.ToBase64String(Encoding.UTF8.GetBytes($"{yourUserId}:{hash}"));

    // Create headers dictionary.
    return new Dictionary<string, string>
    {
        { "Authorization", $"Basic {authentication}" },
        { "Timestamp", timestamp }
    };
}

// Create HTTP client with authentication headers.
var client = new HttpClient();
client.BaseAddress = new Uri("https://www.quantconnect.com/api/v2/");
foreach (var header in GetHeaders())
{
    client.DefaultRequestHeaders.Add(header.Key, header.Value);
}

// Make POST request.
var request = new StringContent("{}", Encoding.UTF8, "application/json");
var response = await client.PostAsync("account/read", request);
var content = await response.Content.ReadAsStringAsync();
Console.WriteLine($"Response: {response.StatusCode}");
Console.WriteLine(content);