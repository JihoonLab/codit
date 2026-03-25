f, b, c, s = map(int, input().split())
size = f * b * c * s / 8 / 1024 / 1024
print(round(size, 1), "MiB")
