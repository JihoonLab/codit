n = int(input())
for i in range(1, 16):
    r = n * i
    print("%X*%X=%X" % (n, i, r))
