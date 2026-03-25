grid = []
for i in range(10):
    grid.append(list(map(int, input().split())))
# Ant starts at (1,1), moves right, then down when blocked
x, y = 1, 1
while True:
    if grid[x][y] == 2:
        grid[x][y] = 9
        break
    grid[x][y] = 9
    if x == 9 and y == 9:
        break
    if y + 1 < 10 and grid[x][y+1] != 1:
        y += 1
    elif x + 1 < 10 and grid[x+1][y] != 1:
        x += 1
    else:
        break
for i in range(10):
    print(' '.join(map(str, grid[i])))
