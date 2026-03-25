#include <stdio.h>
int main() {
    int maze[10][10];
    int i, j;
    for (i = 0; i < 10; i++)
        for (j = 0; j < 10; j++)
            scanf("%d", &maze[i][j]);
    /* Ant starts at (1,1), moves right, then down when blocked */
    int x = 1, y = 1;
    while (1) {
        if (maze[x][y] == 2) { maze[x][y] = 9; break; }
        maze[x][y] = 9;
        if (x == 9 && y == 9) break;
        /* Try right first */
        if (y + 1 < 10 && maze[x][y + 1] != 1) {
            y++;
        } else if (x + 1 < 10 && maze[x + 1][y] != 1) {
            x++;
        } else {
            break;
        }
    }
    for (i = 0; i < 10; i++) {
        for (j = 0; j < 10; j++) {
            if (j > 0) printf(" ");
            printf("%d", maze[i][j]);
        }
        printf("\n");
    }
    return 0;
}
