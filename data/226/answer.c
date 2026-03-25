#include <stdio.h>
int main(void) {
    int n, a[100][100] = {0};
    scanf("%d", &n);
    int cnt = 1, top = 0, bot = n - 1, left = 0, right = n - 1;
    while (cnt <= n * n) {
        for (int i = bot; i >= top && cnt <= n * n; i--) a[i][left] = cnt++;
        left++;
        for (int j = left; j <= right && cnt <= n * n; j++) a[top][j] = cnt++;
        top++;
        for (int i = top; i <= bot && cnt <= n * n; i++) a[i][right] = cnt++;
        right--;
        for (int j = right; j >= left && cnt <= n * n; j--) a[bot][j] = cnt++;
        bot--;
    }
    for (int i = 0; i < n; i++) {
        for (int j = 0; j < n; j++) printf("%d ", a[i][j]);
        printf("\n");
    }
    return 0;
}
