a, b = map(int, input().split())
s = 0
week = 0
while s < b:
    s += a
    week += 1
print(week)
