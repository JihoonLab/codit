#include <stdio.h>
int main(void) {
    int n, k, i, j, val, mn;
    scanf("%d %d", &n, &k);
    for (i = 0; i < n; i += k) {
        int cnt = k;
        if (i + k > n) cnt = n - i;
        for (j = 0; j < cnt; j++) {
            scanf("%d", &val);
            if (j == 0) mn = val;
            else if (val < mn) mn = val;
        }
        printf("%d ", mn);
    }
    printf("\n");
    return 0;
}
