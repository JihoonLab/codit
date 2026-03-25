#include <stdio.h>
int main(void) {
    int n, i, j, a[100][100];
    scanf("%d", &n);
    int cnt = 1;
    for (j = 0; j < n; j++) {
        if (j % 2 == 0) {
            for (i = n - 1; i >= 0; i--) a[i][j] = cnt++;
        } else {
            for (i = 0; i < n; i++) a[i][j] = cnt++;
        }
    }
    for (i = 0; i < n; i++) {
        for (j = 0; j < n; j++) printf("%d ", a[i][j]);
        printf("\n");
    }
    return 0;
}
