n, g = map(int, input().split())
count = 0
for x in input().split():
    if int(x) >= g:
        count += 1
print(count)
