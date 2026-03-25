#include <stdio.h>
int main(void) {
    int n, k, i, j, val, mx;
    scanf("%d %d", &n, &k);
    for (i = 0; i < n; i += k) {
        int cnt = k;
        if (i + k > n) cnt = n - i;
        for (j = 0; j < cnt; j++) {
            scanf("%d", &val);
            if (j == 0) mx = val;
            else if (val > mx) mx = val;
        }
        printf("%d ", mx);
    }
    printf("\n");
    return 0;
}
