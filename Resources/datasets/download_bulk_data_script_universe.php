<div class="section-example-container">
    <pre class="python">import os
from datetime import datetime, time, timedelta
from pytz import timezone
from os.path import abspath, dirname
os.chdir(dirname(abspath(__file__)))

OVERWRITE = False

def __get_start_date() -> str:
    dir_name = f"<?=$dirName?>"
    files = [] if not os.path.exists(dir_name) else sorted(os.listdir(dir_name)) 
    return files[-1].split(".")[0] if files else '19980101'

def __get_end_date() -> str:
    now = datetime.now(timezone("US/Eastern"))
    if now.time() > time(7, 0):
        return (now - timedelta(1)).strftime("%Y%m%d")
    print('New data is available at 07:00 AM EST')
    return (now - timedelta(2)).strftime("%Y%m%d")

if __name__ == "__main__":
    start, end = __get_start_date(), __get_end_date()
    if start >= end:
        exit("Your data is already up to date.")
            
    command = f'lean data download --dataset "<?=$dataset?>" --data-type "Bulk" --start {start} --end {end}'
    if OVERWRITE:
        command += " --overwrite"
    print(command)
    os.system(command)</pre>
</div>