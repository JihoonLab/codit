a, b, c = map(int, input().split())
for x in [a, b, c]:
    print("even" if x % 2 == 0 else "odd")
