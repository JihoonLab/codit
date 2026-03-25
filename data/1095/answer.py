grid = []
for i in range(19):
    row = list(map(int, input().split()))
    grid.append(row)
n = int(input())
for _ in range(n):
    x, y = map(int, input().split())
    x -= 1
    y -= 1
    for j in range(19):
        grid[x][j] = 1 - grid[x][j]
    for i in range(19):
        grid[i][y] = 1 - grid[i][y]
for i in range(19):
    print(' '.join(str(x) for x in grid[i]) + ' ')
