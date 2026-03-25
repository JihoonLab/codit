#include <stdio.h>
int main() {
    int a[20][20] = {};
    int n, i, j, x, y;
    for (i = 1; i <= 19; i++)
        for (j = 1; j <= 19; j++)
            scanf("%d", &a[i][j]);
    scanf("%d", &n);
    for (i = 0; i < n; i++) {
        scanf("%d %d", &x, &y);
        for (j = 1; j <= 19; j++) {
            a[x][j] = a[x][j] ? 0 : 1;
        }
        for (j = 1; j <= 19; j++) {
            a[j][y] = a[j][y] ? 0 : 1;
        }
    }
    for (i = 1; i <= 19; i++) {
        for (j = 1; j <= 19; j++) {
            printf("%d ", a[i][j]);
        }
        printf("\n");
    }
    return 0;
}
