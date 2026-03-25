#include <stdio.h>
int main(void) {
    int m, n, i, j, a[100][100];
    scanf("%d %d", &m, &n);
    int cnt = 1;
    for (j = n - 1; j >= 0; j--) {
        if ((n - 1 - j) % 2 == 0) {
            for (i = 0; i < m; i++) a[i][j] = cnt++;
        } else {
            for (i = m - 1; i >= 0; i--) a[i][j] = cnt++;
        }
    }
    for (i = 0; i < m; i++) {
        for (j = 0; j < n; j++) printf("%d ", a[i][j]);
        printf("\n");
    }
    return 0;
}
