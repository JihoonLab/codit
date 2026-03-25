n = int(input())
parts = []
for i in range(1, n + 1):
    if i % 3 == 0:
        parts.append("X")
    else:
        parts.append(str(i))
print(" ".join(parts))
