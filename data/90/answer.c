#include <stdio.h>
int main() {
    int n, score, count = 0;
    scanf("%d", &n);
    for (int i = 0; i < n; i++) {
        scanf("%d", &score);
        if (score >= 60) count++;
    }
    printf("%d\n", count);
    return 0;
}
