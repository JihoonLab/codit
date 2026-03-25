#include <stdio.h>
int main(void) {
    int m, n, i, j, a[100][100];
    scanf("%d %d", &m, &n);
    int cnt = 1;
    for (i = m - 1; i >= 0; i--) {
        if ((m - 1 - i) % 2 == 0) {
            for (j = 0; j < n; j++) a[i][j] = cnt++;
        } else {
            for (j = n - 1; j >= 0; j--) a[i][j] = cnt++;
        }
    }
    for (i = 0; i < m; i++) {
        for (j = 0; j < n; j++) printf("%d ", a[i][j]);
        printf("\n");
    }
    return 0;
}
