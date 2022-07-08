import gc
import glob
import os
import requests
import threading
import time

def get_url_status(url, files):
    try:
        r = requests.get(url)
        if r.status_code == 404:
            print("\033[2;31;43m 404 Status:\n\t" + url + "\n\t- " + "\n\t- ".join(files))
    except Exception as e:
        print(url + "\n\t" + str(e) + "\n\t- " + "\n\t- ".join(files))
    return None

if __name__ == '__main__':
    root = "https://www.quantconnect.com/"
    quote = "'"
    double_quote = '"'
    urls = {}

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
                        urls[url] = []
                    urls[url].append(filename)

    start = time.perf_counter()
    threads = []

    for i, (url, files) in enumerate(urls.items()):
        t = threading.Thread(target=get_url_status, args=[url, files])
        t.start()
        threads.append(t)
        
        if i % 8 == 0:
            for thread in threads:
                thread.join()
            
            gc.collect()                
            print(f"Done {i+1}/{len(urls)} ({i*100/len(urls):.2f}%)")
        
    end = time.perf_counter()
    print(f'Finished in {round(end-start, 2)} second(s)') 