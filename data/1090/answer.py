import math
a, b, c = map(int, input().split())
def lcm(x, y):
    return x * y // math.gcd(x, y)
print(lcm(lcm(a, b), c))
