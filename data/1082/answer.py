h, m, s = map(int, input().split())
count = 0
for i in range(h):
    for j in range(m):
        for k in range(s):
            print(i, j, k)
            count += 1
print(count)
