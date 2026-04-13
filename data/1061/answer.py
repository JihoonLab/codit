n, m = map(int, input().split())
s = 0
day = 0
for x in input().split():
    s += int(x)
    day += 1
    if s > m:
        break
if s > m:
    print(day)
else:
    print("SAFE")
