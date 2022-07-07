import glob
import os
import requests

def get_url_status(urls):
    for url in urls:
        try:
            r = requests.get(url)
            if r.status_code == 404:
                print("404 Status: " + url)
        except Exception as e:
            print(url + "\tNA FAILED TO CONNECT\t" + str(e))
            

root = "https://www.quantconnect.com/"
quote = "'"
double_quote = '"'
urls = []

for root_dir in ["01 Our Platform/", "02 Writing Algorithms/", "03 Research Environment/", "05 Lean CLI/", "99 LEAN Engine/"]:
    for filename in list(glob.iglob(root_dir + "**/*.html", recursive=True)) + \
                    list(glob.iglob(root_dir + "**/*.php", recursive=True)) + \
                    list(glob.iglob(root_dir + "**/*.json", recursive=True)):
        filename = filename.replace("\\", os.path.sep).replace("/", os.path.sep)
        content = open(filename, 'r', encoding="utf-8").read()
        
        if "a href" in content:
            subcontent = content.split('a href="')[1:]
            hrefs = [x.split(quote)[0].split(double_quote)[0] for x in subcontent if x]
            
            for url in hrefs:
                if "http" not in url:
                    if url[0] != "/":
                        sub_url = "/".join([x[3:].replace(" ", "-").lower() for x in filename.split(os.path.sep)[:-2]])
                        url = f"{root}{sub_url}/{url}"
                        
                    else:
                        url = f"{root}{url}"
                
                url = url.replace("//", "/").replace(":/", "://")
                if url not in urls:
                    urls.append(url)

get_url_status(urls)