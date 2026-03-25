a, m, d, n = map(int, input().split())
x = a
for _ in range(n - 1):
    x = m * x + d
print(x)
