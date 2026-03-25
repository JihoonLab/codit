#include <stdio.h>
int main(void) {
    int m, n, a[100][100] = {0};
    scanf("%d %d", &m, &n);
    int cnt = 1, top = 0, bot = m - 1, left = 0, right = n - 1;
    int total = m * n;
    while (cnt <= total) {
        for (int i = top; i <= bot && cnt <= total; i++) a[i][right] = cnt++;
        right--;
        for (int j = right; j >= left && cnt <= total; j--) a[bot][j] = cnt++;
        bot--;
        for (int i = bot; i >= top && cnt <= total; i--) a[i][left] = cnt++;
        left++;
        for (int j = left; j <= right && cnt <= total; j++) a[top][j] = cnt++;
        top++;
    }
    for (int i = 0; i < m; i++) {
        for (int j = 0; j < n; j++) printf("%d ", a[i][j]);
        printf("\n");
    }
    return 0;
}
