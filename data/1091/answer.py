n = int(input())
bins = [0] * 23
for _ in range(n):
    v = int(input())
    bins[v - 1] += 1
print(" ".join(map(str, bins)))
