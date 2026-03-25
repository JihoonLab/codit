#include <stdio.h>
int main() {
    int h, w, n;
    int grid[101][101] = {};
    int i, j, l, d, x, y;
    scanf("%d %d", &h, &w);
    scanf("%d", &n);
    for (i = 0; i < n; i++) {
        scanf("%d %d %d %d", &l, &d, &x, &y);
        for (j = 0; j < l; j++) {
            if (d == 0) {
                grid[x][y + j] = 1;
            } else {
                grid[x + j][y] = 1;
            }
        }
    }
    for (i = 1; i <= h; i++) {
        for (j = 1; j <= w; j++) {
            printf("%d ", grid[i][j]);
        }
        printf("\n");
    }
    return 0;
}
