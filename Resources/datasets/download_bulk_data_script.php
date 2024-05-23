<div class="section-example-container">
    <pre class="python">import os
import pandas as pd
from datetime import datetime, time, timedelta
from pytz import timezone
from os.path import abspath, dirname
os.chdir(dirname(abspath(__file__)))

OVERWRITE = False

# Define a method to download the data
def __download_data(resolution, start=None, end=None):
    print(f"Updating {resolution} data...")
    command = f'lean data download --dataset "<?=$dataset?>" --data-type "Bulk" <?=$extraArgs?> --resolution "{resolution}"'
    if start:
        end = end if end else start
        command += f" --start {start} --end {end}"
    if OVERWRITE:
        command += " --overwrite"
    print(command)
    os.system(command)

def __get_end_date() -> str:
    now = datetime.now(timezone("US/Eastern"))
    if now.time() > time(7,30):
        return (now - timedelta(1)).strftime("%Y%m%d")
    print('New data is available at 07:30 AM EST')
    return (now - timedelta(2)).strftime("%Y%m%d")

def __download_high_frequency_data(latest_on_cloud):
    for resolution in <?=$highResolutions?>:
        dir_name = f"<?=$securityType?>/<?=$market?>/{resolution}/<?=$ticker?>".lower()
        if not os.path.exists(dir_name):
            __download_data(resolution, '19980101')
            continue
        latest_on_disk = sorted(os.listdir(dir_name))[-1].split('_')[0]
        if latest_on_disk >= latest_on_cloud:
            print(f"{resolution} data is already up to date.")
            continue
        __download_data(resolution, latest_on_disk, latest_on_cloud)

def __download_low_frequency_data(latest_on_cloud):
    for resolution in ["daily", "hour"]:
        file_name = f"<?=$securityType?>/<?=$market?>/{resolution}/<?=$ticker?>.zip".lower()
        if not os.path.exists(file_name):
            __download_data(resolution)
            continue
        latest_on_disk = str(pd.read_csv(file_name, header=None)[0].iloc[-1])[:8]
        if latest_on_disk >= latest_on_cloud:
            print(f"{resolution} data is already up to date.")
            continue
        __download_data(resolution)

if __name__ == "__main__":
    latest_on_cloud = __get_end_date()
    __download_low_frequency_data(latest_on_cloud)
    __download_high_frequency_data(latest_on_cloud)</pre>
</div>