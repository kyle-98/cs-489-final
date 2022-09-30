import re

data_list = []
with open("tropical_data_noaa.txt", "r") as infile, open("new_output.txt", "w") as outfile:
    temp_data_2 = ""
    for line in infile:
        temp_data = line.replace(" ", "")
        temp_data = temp_data.rstrip()
        temp_data = temp_data.split(",")
        if temp_data[0].startswith("AL"):   
            temp_data.pop(3)
            temp_data.pop(2)
            outfile.write(f"\n ('{temp_data[1]}', {temp_data[0][-4:]}, '")
            #outfile.write(f"\n{temp_data[0]}, {temp_data[1]}")
            #outfile.write(f"\n ('{temp_data[1]}', {temp_data[0][-4:]}, '")
        else:
            temp_data[0] = temp_data[0][4:]
            temp_data.pop(2)
            #print(temp_data)
            #for i in range(3):
            #    temp_data.pop(0)
            for i in range(13):
                temp_data.pop(5)
            
            
            #outfile.write(f"\n\t{temp_data[0]}, {coords_list}, {temp_data[3]}, {temp_data[4]}")
            coords_list = f"[{float(temp_data[3][:-1])}, {float(temp_data[4][:-1])}]"
            outfile.write(f"{coords_list}, ")


        temp_data = (",").join(temp_data)
        temp_data_2 += temp_data
    split_data = temp_data_2.split("AL")


infile.close()
outfile.close()


with open("tropical_data_noaa.txt", "r") as infile, open("new_output_2.txt", "w") as outfile:
    temp_data_2 = ""
    for line in infile:
        temp_data = line.replace(" ", "")
        temp_data = temp_data.rstrip()
        temp_data = temp_data.split(",")
        if temp_data[0].startswith("AL"):   
            temp_data.pop(3)
            temp_data.pop(2)
            #outfile.write(f"\n{temp_data[0]}, {temp_data[1]}")
            #outfile.write(f"\n '{temp_data[1]}', {temp_data[0][-4:]}, '")
            outfile.write(f"\n'")
        else:
            for i in range(13):
                temp_data.pop(8)
            #print(temp_data)
            date_list = f"{temp_data[0][4:6]}-{temp_data[0][6:]}"
            time_list = f"{temp_data[1][0:2]}:{temp_data[1][2:]}"
            other_list = f"{temp_data[3]} {temp_data[6]} {temp_data[7]}"
            outfile.write(f"{date_list} {time_list} {other_list}, ")


        temp_data = (",").join(temp_data)
        temp_data_2 += temp_data
    split_data = temp_data_2.split("AL")


infile.close()
outfile.close()


"""
storms_and_coords = []
with open("coords.txt", "r") as infile, open("", "w"):
    for line in infile:
        temp = re.split(" > |, ", line)
        print(temp)
"""

"""
for d in split_data:
    temp = []
    d = d.replace(" ", "")
    d = re.split(",|\n",d)
    d = list(filter(None, d))
    data_list.append(d)
data_list.pop(0)
"""

#print(data_list)
