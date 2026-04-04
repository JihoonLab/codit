#include <stdio.h>
int main() {
    int n;
    scanf("%d", &n);
    for (int i = 1; i <= n; i++) {
        int tmp = i, cnt = 0;
        while (tmp > 0) {
            int d = tmp % 10;
            if (d == 3 || d == 6 || d == 9) cnt++;
            tmp /= 10;
        }
        if (cnt > 0) {
            for (int j = 0; j < cnt; j++) printf("짝");
            printf("\n");
        } else {
            printf("%d\n", i);
        }
    }
    return 0;
}
