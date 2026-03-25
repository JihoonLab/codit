#include <stdio.h>
int main(void) {
    int m, n, i, j, a[100][100];
    scanf("%d %d", &m, &n);
    int cnt = 1, s, r;
    for (s = 0; s < m + n - 1; s++) {
        if (s % 2 == 1) {
            int start_i = s < m ? s : m - 1;
            int end_i = s < n ? 0 : s - n + 1;
            for (r = start_i; r >= end_i; r--) {
                a[r][s - r] = cnt++;
            }
        } else {
            int start_i = s < n ? 0 : s - n + 1;
            int end_i = s < m ? s : m - 1;
            for (r = start_i; r <= end_i; r++) {
                a[r][s - r] = cnt++;
            }
        }
    }
    for (i = 0; i < m; i++) {
        for (j = 0; j < n; j++) printf("%d ", a[i][j]);
        printf("\n");
    }
    return 0;
}
