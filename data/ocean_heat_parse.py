import csv


year_data = []
avg_data = []

with open("ncei_ocean_heat_content.csv", newline='') as csvfile:
    reader = csv.reader(csvfile, delimiter=',')
    temp = []
    temp_year = 0
    count = 1
    for row in reader:
        temp.append(float(row[1]))
        temp_year = row[0][:4]
        if count == 4:
            year_data.append(temp_year)
            avg_data.append(round(sum(temp) / len(temp), 2))
            temp = []
            count = 1
        else:
            count += 1
        
        

print(year_data)
print()
print(avg_data)