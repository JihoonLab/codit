h, w = map(int, input().split())
grid = [[0]*w for _ in range(h)]
n = int(input())
for _ in range(n):
    parts = list(map(int, input().split()))
    l, d, x, y = parts[0], parts[1], parts[2], parts[3]
    for j in range(l):
        if d == 0:
            if 1 <= x <= h and 1 <= y+j <= w:
                grid[x-1][y+j-1] = 1
        else:
            if 1 <= x+j <= h and 1 <= y <= w:
                grid[x+j-1][y-1] = 1
for i in range(h):
    print(' '.join(map(str, grid[i])))
