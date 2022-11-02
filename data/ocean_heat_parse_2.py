import json
import requests

main_url = "https://www.ncei.noaa.gov/access/monitoring/climate-at-a-glance/global/time-series/atlanticMdr/land_ocean/12/12/2000-2022/data.json?trend=true&trend_base=10&firsttrendyear=1910&lasttrendyear=2021"
res = requests.get(main_url)
data = res.json()

t_list = []

for i in data['data']:
    t_list.append(float(data['data'].get(i)))

print(t_list)