import re
import calendar
from datetime import date

def organize_starting_months():
    with open("tropical_data_noaa.txt", "r") as infile, open("starting_months.txt", "w") as outfile:
        count = 0
        temp_month_list = []
        for line in infile:
            temp_data = line.replace(" ", "")
            temp_data = temp_data.rstrip()
            temp_data = temp_data.split(",")
            if temp_data[0].startswith("AL"):   
                temp_data.pop(3)
                temp_data.pop(2)
                count= 0
            else:
                if count == 0:
                    for i in range(13):
                        temp_data.pop(8)
                    date_list = f"{temp_data[0][4:6]}"
                    
                    if date_list[0] == "0":
                        date_list = date_list[1]
                        
                    temp_month_list.append(date_list)
                    
                count += 1

        for t in temp_month_list:
            a = calendar.month_name[int(t)]
            outfile.write(f"\n,'{a}', ")
    infile.close()
    outfile.close()

def organize_winds():
    with open("tropical_data_noaa.txt", "r") as infile, open("highest_wind.txt", "w") as outfile:
        temp_wind_speed = 0
        
        temp_wind_speed_list = []
        for line in infile:
            temp_data = line.replace(" ", "")
            temp_data = temp_data.rstrip()
            temp_data = temp_data.split(",")
            if temp_data[0].startswith("AL"):   
                temp_data.pop(3)
                temp_data.pop(2)
                temp_wind_speed_list.append(temp_wind_speed)
                
                temp_wind_speed = 0
                
            else:

                for i in range(13):
                    temp_data.pop(8)
                wind_speed = int(temp_data[6])
                if wind_speed > temp_wind_speed:
                    temp_wind_speed = wind_speed
                
        
        temp_wind_speed_list.pop(0)
        temp_wind_speed_list.append(60)
        for speed in temp_wind_speed_list:
            outfile.write(f"\n{speed}")



    infile.close()
    outfile.close()


def organize_duration():
    with open("tropical_data_noaa.txt", "r") as infile, open("duration.txt", "w") as outfile:
        count = 0
        temp_duration_list = []
        date_list = ()
        t = ()
        for line in infile:
            temp_data = line.replace(" ", "")
            temp_data = temp_data.rstrip()
            temp_data = temp_data.split(",")
            if temp_data[0].startswith("AL"): 
                
                temp_data.pop(3)
                temp_data.pop(2)
                count= 0
                temp_duration_list.append([date_list, t])
            else:
                
                if count == 0:
                    for i in range(13):
                        temp_data.pop(8)
                    
                    date_list = (int(temp_data[0][0:4]), int(temp_data[0][4:6]), int(temp_data[0][6:]))

                    
                    if date_list[0] == "0":
                        date_list = date_list[1]
                        
                    
                t = (int(temp_data[0][0:4]), int(temp_data[0][4:6]), int(temp_data[0][6:]))
                count += 1

    
        temp_duration_list.pop(0)
        temp_duration_list.append([(2021, 10, 25), (2021, 11, 8)])
        duration_list = []
        for d in temp_duration_list:
            d1 = date(d[0][0], d[0][1], d[0][2])
            d2 = date(d[1][0], d[1][1], d[1][2])
            duration_list.append((d2 - d1).days)

        for d in duration_list:
            outfile.write(f"\n, {d}")

    infile.close()
    outfile.close()

organize_duration()