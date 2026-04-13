a, b = map(int, input().split())
s, e = min(a, b), max(a, b)
for i in range(s, e+1):
    print(i, end=" ")
